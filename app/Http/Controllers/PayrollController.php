<?php

namespace App\Http\Controllers;
 
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
 
class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->bulan ?? Carbon::now()->month;
        $tahun  = $request->tahun ?? Carbon::now()->year;
 
        $payrolls = Payroll::with(['karyawan.jabatan', 'karyawan.departement'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->paginate(20);
 
        $summary = Payroll::where('bulan', $bulan)->where('tahun', $tahun)
            ->selectRaw('SUM(gaji_bersih) as total, COUNT(*) as jumlah, status')
            ->groupBy('status')->get()->keyBy('status');
 
        return view('payroll.index', compact('payrolls', 'bulan', 'tahun', 'summary'));
    }
 
    public function generate(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
        ]);
 
        $bulan = $request->bulan;
        $tahun = $request->tahun;
 
        // Hitung hari kerja di bulan tsb (Senin-Jumat)
        $hariKerja = 0;
        $awal  = Carbon::createFromDate($tahun, $bulan, 1);
        $akhir = $awal->copy()->endOfMonth();
        for ($d = $awal->copy(); $d->lte($akhir); $d->addDay()) {
            if (!$d->isWeekend()) $hariKerja++;
        }
 
        $karyawans = Karyawan::with(['tunjangens', 'potongans'])->where('status', 'aktif')->get();
        $generated = 0;
        $skipped   = 0;
 
        DB::transaction(function () use ($karyawans, $bulan, $tahun, $hariKerja, &$generated, &$skipped) {
            foreach ($karyawans as $k) {
                // Skip jika sudah ada dan bukan draft
                $existing = Payroll::where('karyawan_id', $k->id)->where('bulan', $bulan)->where('tahun', $tahun)->first();
                if ($existing && $existing->status !== 'draft') { $skipped++; continue; }
 
                // Hitung absensi
                $absensis = Absensi::where('karyawan_id', $k->id)
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get();
 
                $hadir  = $absensis->where('status', 'hadir')->count();
                $izin   = $absensis->where('status', 'izin')->count();
                $sakit  = $absensis->where('status', 'sakit')->count();
                $alpha  = $absensis->where('status', 'alpha')->count();
                $lembur = $absensis->sum('jam_lembur');
 
                // Hitung gaji
                $gajiPokok     = $k->gaji_pokok;
                $gajiHarian    = $hariKerja > 0 ? $gajiPokok / $hariKerja : 0;
                $tunjangan     = $k->tunjangens->sum('nominal');
                $potongan      = $k->potongans->sum('nominal');
                $uangLembur    = $lembur * ($gajiHarian / 8) * 1.5; // 1.5x upah jam
                $potAlpha      = $alpha * $gajiHarian;
 
                $gajiBersih = $gajiPokok + $tunjangan + $uangLembur - $potongan - $potAlpha;
                $gajiBersih = max($gajiBersih, 0);
 
                // Hapus draft lama jika ada
                if ($existing) { $existing->details()->delete(); $existing->delete(); }
 
                $payroll = Payroll::create([
                    'karyawan_id'      => $k->id,
                    'bulan'            => $bulan,
                    'tahun'            => $tahun,
                    'hari_kerja'       => $hariKerja,
                    'hari_hadir'       => $hadir,
                    'hari_izin'        => $izin,
                    'hari_sakit'       => $sakit,
                    'hari_alpha'       => $alpha,
                    'total_jam_lembur' => $lembur,
                    'gaji_pokok'       => $gajiPokok,
                    'total_tunjangan'  => $tunjangan,
                    'uang_lembur'      => $uangLembur,
                    'total_potongan'   => $potongan,
                    'potongan_alpha'   => $potAlpha,
                    'gaji_bersih'      => $gajiBersih,
                    'status'           => 'draft',
                    'dibuat_oleh'      => Auth::id(),
                ]);
 
                // Simpan detail
                foreach ($k->tunjangens as $t) {
                    PayrollDetail::create(['payroll_id' => $payroll->id, 'tipe' => 'tunjangan', 'keterangan' => $t->nama_tunjangan, 'nominal' => $t->nominal]);
                }
                foreach ($k->potongans as $p) {
                    PayrollDetail::create(['payroll_id' => $payroll->id, 'tipe' => 'potongan', 'keterangan' => $p->nama_potongan, 'nominal' => $p->nominal]);
                }
                if ($uangLembur > 0) {
                    PayrollDetail::create(['payroll_id' => $payroll->id, 'tipe' => 'lembur', 'keterangan' => "Lembur {$lembur} jam", 'nominal' => $uangLembur]);
                }
                if ($potAlpha > 0) {
                    PayrollDetail::create(['payroll_id' => $payroll->id, 'tipe' => 'alpha', 'keterangan' => "Potongan Alpha {$alpha} hari", 'nominal' => $potAlpha]);
                }
 
                $generated++;
            }
        });
 
        return redirect()->route('payroll.index', ['bulan' => $bulan, 'tahun' => $tahun])
                         ->with('success', "Payroll berhasil di-generate! {$generated} karyawan diproses, {$skipped} dilewati.");
    }
 
    public function approve(Payroll $payroll)
    {
        if ($payroll->status !== 'draft') {
            return back()->with('error', 'Hanya payroll berstatus Draft yang bisa di-approve!');
        }
        $payroll->update([
            'status'          => 'approved',
            'diapprove_oleh'  => Auth::id(),
            'approved_at'     => now(),
        ]);
        return back()->with('success', 'Payroll berhasil di-approve!');
    }
 
    public function approveAll(Request $request)
    {
        $request->validate(['bulan' => 'required', 'tahun' => 'required']);
        $count = Payroll::where('bulan', $request->bulan)->where('tahun', $request->tahun)
            ->where('status', 'draft')->update([
                'status'         => 'approved',
                'diapprove_oleh' => Auth::id(),
                'approved_at'    => now(),
            ]);
        return back()->with('success', "{$count} payroll berhasil di-approve!");
    }
 
    public function markPaid(Payroll $payroll)
    {
        if ($payroll->status !== 'approved') {
            return back()->with('error', 'Hanya payroll Approved yang bisa ditandai Lunas!');
        }
        $payroll->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Payroll ditandai Lunas!');
    }
 
    public function markAllPaid(Request $request)
    {
        $request->validate(['bulan' => 'required', 'tahun' => 'required']);
        $count = Payroll::where('bulan', $request->bulan)->where('tahun', $request->tahun)
            ->where('status', 'approved')->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', "{$count} payroll ditandai Lunas!");
    }
 
    public function show(Payroll $payroll)
    {
        $payroll->load(['karyawan.jabatan', 'karyawan.departement', 'details', 'dibuatOleh', 'diapproveOleh']);
        return view('payroll.show', compact('payroll'));
    }
}