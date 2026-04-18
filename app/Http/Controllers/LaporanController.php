<?php

namespace App\Http\Controllers;
 
use App\Models\Payroll;
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
 
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan  = $request->bulan ?? Carbon::now()->month;
        $tahun  = $request->tahun ?? Carbon::now()->year;
 
        $payrolls = Payroll::with(['karyawan.jabatan', 'karyawan.departement'])
            ->where('bulan', $bulan)->where('tahun', $tahun)
            ->where('status', '!=', 'draft')
            ->orderBy('created_at')->get();
 
        $totalGaji     = $payrolls->sum('gaji_bersih');
        $totalTunjangan = $payrolls->sum('total_tunjangan');
        $totalPotongan  = $payrolls->sum('total_potongan');
 
        return view('laporan.index', compact('payrolls', 'bulan', 'tahun', 'totalGaji', 'totalTunjangan', 'totalPotongan'));
    }
 
    public function exportPdf(Request $request)
    {
        $bulan    = $request->bulan ?? Carbon::now()->month;
        $tahun    = $request->tahun ?? Carbon::now()->year;
        $payrolls = Payroll::with(['karyawan.jabatan', 'karyawan.departement'])
            ->where('bulan', $bulan)->where('tahun', $tahun)
            ->where('status', '!=', 'draft')->get();
 
        $pdf = Pdf::loadView('laporan.pdf', compact('payrolls', 'bulan', 'tahun'))
                  ->setPaper('A4', 'landscape');
 
        return $pdf->download("laporan_gaji_{$bulan}_{$tahun}.pdf");
    }
 
    public function rekap(Request $request)
    {
        $tahun = $request->tahun ?? Carbon::now()->year;
        $karyawan_id = $request->karyawan_id;
 
        $data = Payroll::with('karyawan')
            ->where('tahun', $tahun)
            ->when($karyawan_id, fn($q) => $q->where('karyawan_id', $karyawan_id))
            ->where('status', '!=', 'draft')
            ->orderBy('bulan')->get();
 
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();
 
        return view('laporan.rekap', compact('data', 'tahun', 'karyawans', 'karyawan_id'));
    }
}