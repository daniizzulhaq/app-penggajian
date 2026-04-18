<?php

// ================================================================
// FILE: app/Http/Controllers/AbsensiController.php
// ================================================================
namespace App\Http\Controllers;
 
use App\Models\Absensi;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
 
class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan     = $request->bulan ?? Carbon::now()->month;
        $tahun     = $request->tahun ?? Carbon::now()->year;
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_lengkap')->get();
 
        $absensis = Absensi::with('karyawan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->when($request->karyawan_id, fn($q) => $q->where('karyawan_id', $request->karyawan_id))
            ->orderBy('tanggal', 'desc')
            ->paginate(20);
 
        return view('absensi.index', compact('absensis', 'karyawans', 'bulan', 'tahun'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawans,id',
            'tanggal'     => 'required|date',
            'status'      => 'required|in:hadir,izin,sakit,alpha',
            'jam_lembur'  => 'nullable|numeric|min:0|max:24',
        ]);
 
        Absensi::updateOrCreate(
            ['karyawan_id' => $request->karyawan_id, 'tanggal' => $request->tanggal],
            [
                'status'      => $request->status,
                'jam_lembur'  => $request->jam_lembur ?? 0,
                'keterangan'  => $request->keterangan,
            ]
        );
 
        return back()->with('success', 'Absensi berhasil disimpan!');
    }
 
    public function importBulk(Request $request)
    {
        // Tandai semua karyawan aktif hadir pada tanggal tertentu
        $request->validate(['tanggal' => 'required|date']);
 
        $karyawans = Karyawan::where('status', 'aktif')->get();
        foreach ($karyawans as $k) {
            Absensi::updateOrCreate(
                ['karyawan_id' => $k->id, 'tanggal' => $request->tanggal],
                ['status' => 'hadir', 'jam_lembur' => 0]
            );
        }
 
        return back()->with('success', 'Absensi massal berhasil diinput!');
    }
 
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return back()->with('success', 'Data absensi dihapus!');
    }
}
