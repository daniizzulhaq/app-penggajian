<?php

namespace App\Http\Controllers;
 
use App\Models\Karyawan;
use App\Models\Payroll;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
 
class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;
 
        if ($user->isAdmin()) {
            $data = [
                'total_karyawan'  => Karyawan::where('status', 'aktif')->count(),
                'total_payroll'   => Payroll::where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'payroll_draft'   => Payroll::where('status', 'draft')->where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'payroll_paid'    => Payroll::where('status', 'paid')->where('bulan', $bulan)->where('tahun', $tahun)->count(),
                'total_gaji'      => Payroll::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'paid')->sum('gaji_bersih'),
                'absensi_hari_ini'=> Absensi::where('tanggal', Carbon::today())->count(),
            ];
            return view('dashboard.admin', compact('data'));
        }
 
        // Karyawan
        $karyawan = $user->karyawan;
        if (!$karyawan) {
            return view('dashboard.karyawan', ['payrolls' => collect(), 'karyawan' => null]);
        }
 
        $payrolls = Payroll::where('karyawan_id', $karyawan->id)
                           ->where('status', '!=', 'draft')
                           ->orderByDesc('tahun')->orderByDesc('bulan')
                           ->take(6)->get();
 
        return view('dashboard.karyawan', compact('payrolls', 'karyawan'));
    }
}