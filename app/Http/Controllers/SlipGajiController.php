<?php

namespace App\Http\Controllers;
 
use App\Models\Payroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
 
class SlipGajiController extends Controller
{
    // Karyawan lihat slip gaji sendiri
    public function mySlip()
    {
        $karyawan = Auth::user()->karyawan;
        if (!$karyawan) {
            return view('slip.my_slip', ['payrolls' => collect(), 'karyawan' => null]);
        }
        $payrolls = Payroll::where('karyawan_id', $karyawan->id)
            ->where('status', '!=', 'draft')
            ->orderByDesc('tahun')->orderByDesc('bulan')->paginate(12);
        return view('slip.my_slip', compact('payrolls', 'karyawan'));
    }
 
    // Cetak slip PDF
    public function cetak(Payroll $payroll)
    {
        $user = Auth::user();
        // Karyawan hanya bisa lihat slip sendiri
        if ($user->isKaryawan()) {
            $karyawan = $user->karyawan;
            if (!$karyawan || $payroll->karyawan_id !== $karyawan->id) {
                abort(403, 'Anda tidak bisa mengakses slip gaji orang lain.');
            }
        }
 
        if ($payroll->status === 'draft') {
            abort(403, 'Slip gaji belum tersedia (masih Draft).');
        }
 
        $payroll->load(['karyawan.jabatan', 'karyawan.departement', 'details']);
        $pdf = Pdf::loadView('slip.cetak', compact('payroll'))
                  ->setPaper('A4', 'portrait');
 
        $filename = "slip_gaji_{$payroll->karyawan->nik}_{$payroll->bulan}_{$payroll->tahun}.pdf";
        return $pdf->stream($filename);
    }
}
 