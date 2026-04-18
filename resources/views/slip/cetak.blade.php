<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: Arial, sans-serif; font-size:12px; color:#333; }
    .container { padding: 30px; max-width: 700px; margin: 0 auto; }
    .header { background:#1e3a5f; color:white; padding:20px; border-radius:8px; margin-bottom:20px; }
    .header h2 { font-size:18px; margin-bottom:4px; }
    .header p { font-size:11px; opacity:0.8; }
    .badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:10px; font-weight:bold; }
    .badge-paid { background:#d4edda; color:#155724; }
    .badge-approved { background:#d1ecf1; color:#0c5460; }
    .info-row { display:flex; justify-content:space-between; margin-bottom:16px; gap:16px; }
    .info-box { flex:1; background:#f8f9fa; padding:12px; border-radius:8px; }
    .info-box label { font-size:10px; color:#666; display:block; margin-bottom:2px; }
    .info-box strong { font-size:12px; }
    table { width:100%; border-collapse:collapse; margin-bottom:16px; }
    table th { background:#1e3a5f; color:white; padding:8px; text-align:left; font-size:11px; }
    table td { padding:7px 8px; border-bottom:1px solid #eee; font-size:11px; }
    .text-right { text-align:right; }
    .text-success { color:#28a745; }
    .text-danger  { color:#dc3545; }
    .total-row td { font-weight:bold; font-size:13px; background:#e8f5e9; border-top:2px solid #28a745; }
    .footer { text-align:center; margin-top:24px; font-size:10px; color:#999; }
    .separator { border:none; border-top:2px dashed #ccc; margin:16px 0; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>💰 SLIP GAJI KARYAWAN</h2>
        <p>Periode: {{ $payroll->nama_bulan }} {{ $payroll->tahun }}</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
 
    <div class="info-row">
        <div class="info-box">
            <label>Nama Karyawan</label>
            <strong>{{ $payroll->karyawan->nama_lengkap }}</strong>
        </div>
        <div class="info-box">
            <label>NIK</label>
            <strong>{{ $payroll->karyawan->nik }}</strong>
        </div>
        <div class="info-box">
            <label>Jabatan</label>
            <strong>{{ $payroll->karyawan->jabatan->nama_jabatan }}</strong>
        </div>
    </div>
 
    <div class="info-row">
        <div class="info-box">
            <label>Departemen</label>
            <strong>{{ $payroll->karyawan->departement->nama_departement }}</strong>
        </div>
        <div class="info-box">
            <label>Hari Kerja / Hadir</label>
            <strong>{{ $payroll->hari_kerja }} hari / {{ $payroll->hari_hadir }} hari</strong>
        </div>
        <div class="info-box">
            <label>Status</label>
            <span class="badge {{ $payroll->status === 'paid' ? 'badge-paid' : 'badge-approved' }}">
                {{ strtoupper($payroll->status) }}
            </span>
        </div>
    </div>
 
    <hr class="separator">
 
    {{-- Rekap Absensi --}}
    <table>
        <tr>
            <th colspan="4">📅 Rekap Kehadiran</th>
        </tr>
        <tr>
            <td>Hadir</td><td><strong>{{ $payroll->hari_hadir }} hari</strong></td>
            <td>Izin</td><td><strong>{{ $payroll->hari_izin }} hari</strong></td>
        </tr>
        <tr>
            <td>Sakit</td><td><strong>{{ $payroll->hari_sakit }} hari</strong></td>
            <td>Alpha</td><td><strong>{{ $payroll->hari_alpha }} hari</strong></td>
        </tr>
        <tr>
            <td>Total Lembur</td><td colspan="3"><strong>{{ $payroll->total_jam_lembur }} jam</strong></td>
        </tr>
    </table>
 
    {{-- Rincian Gaji --}}
    <table>
        <tr>
            <th>Keterangan</th>
            <th class="text-right">Nominal</th>
        </tr>
        <tr>
            <td>Gaji Pokok</td>
            <td class="text-right">Rp {{ number_format($payroll->gaji_pokok,0,',','.') }}</td>
        </tr>
        @foreach($payroll->details->where('tipe','tunjangan') as $d)
        <tr>
            <td class="text-success">+ {{ $d->keterangan }}</td>
            <td class="text-right text-success">Rp {{ number_format($d->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
        @foreach($payroll->details->where('tipe','lembur') as $d)
        <tr>
            <td class="text-success">+ {{ $d->keterangan }}</td>
            <td class="text-right text-success">Rp {{ number_format($d->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
        @foreach($payroll->details->where('tipe','potongan') as $d)
        <tr>
            <td class="text-danger">- {{ $d->keterangan }}</td>
            <td class="text-right text-danger">Rp {{ number_format($d->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
        @foreach($payroll->details->where('tipe','alpha') as $d)
        <tr>
            <td class="text-danger">- {{ $d->keterangan }}</td>
            <td class="text-right text-danger">Rp {{ number_format($d->nominal,0,',','.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td>💰 GAJI BERSIH</td>
            <td class="text-right">Rp {{ number_format($payroll->gaji_bersih,0,',','.') }}</td>
        </tr>
    </table>
 
    <div class="footer">
        <p>Slip gaji ini dicetak secara digital oleh Sistem Penggajian Karyawan</p>
        <p>Dokumen ini sah tanpa tanda tangan basah</p>
    </div>
</div>
</body>
</html>
 