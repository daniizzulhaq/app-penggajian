{{-- resources/views/laporan/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Gaji</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h2 { font-size: 16px; font-weight: bold; }
        .header p  { font-size: 12px; margin-top: 4px; }

        .meta { margin-bottom: 14px; font-size: 11px; }
        .meta span { margin-right: 30px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #aaa; padding: 5px 7px; text-align: left; }
        th { background-color: #2d6a4f; color: #fff; text-align: center; font-size: 10px; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        td.number { text-align: right; }
        td.center { text-align: center; }

        .tfoot td { font-weight: bold; background-color: #e8f5e9; }

        .summary { margin-top: 10px; float: right; width: 300px; }
        .summary table { width: 100%; }
        .summary td { border: 1px solid #aaa; padding: 5px 8px; }
        .summary td:last-child { text-align: right; font-weight: bold; }

        .footer { margin-top: 40px; text-align: right; font-size: 10px; color: #666; }

        @page { margin: 15mm; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN PENGGAJIAN KARYAWAN</h2>
        <p>Periode: {{ \Carbon\Carbon::createFromDate(null, (int) $bulan, 1)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <div class="meta">
        <span><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
        <span><strong>Total Karyawan:</strong> {{ $payrolls->count() }} orang</span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:18%">Nama Karyawan</th>
                <th style="width:12%">Jabatan</th>
                <th style="width:12%">Departemen</th>
                <th style="width:12%">Gaji Pokok</th>
                <th style="width:11%">Total Tunjangan</th>
                <th style="width:11%">Total Potongan</th>
                <th style="width:12%">Gaji Bersih</th>
                <th style="width:8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payrolls as $i => $p)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                <td class="center">{{ $p->karyawan->jabatan->nama_jabatan ?? '-' }}</td>
                <td class="center">{{ $p->karyawan->departement->nama_departement ?? '-' }}</td>
                <td class="number">Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($p->total_tunjangan, 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($p->total_potongan, 0, ',', '.') }}</td>
                <td class="number"><strong>Rp {{ number_format($p->gaji_bersih, 0, ',', '.') }}</strong></td>
                <td class="center">{{ ucfirst($p->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="center">Tidak ada data payroll.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="tfoot">
                <td colspan="4" class="center"><strong>TOTAL</strong></td>
                <td class="number">Rp {{ number_format($payrolls->sum('gaji_pokok'), 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($payrolls->sum('total_tunjangan'), 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($payrolls->sum('total_potongan'), 0, ',', '.') }}</td>
                <td class="number">Rp {{ number_format($payrolls->sum('gaji_bersih'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <table>
            <tr><td>Total Gaji Pokok</td><td>Rp {{ number_format($payrolls->sum('gaji_pokok'), 0, ',', '.') }}</td></tr>
            <tr><td>Total Tunjangan</td><td>Rp {{ number_format($payrolls->sum('total_tunjangan'), 0, ',', '.') }}</td></tr>
            <tr><td>Total Potongan</td><td>Rp {{ number_format($payrolls->sum('total_potongan'), 0, ',', '.') }}</td></tr>
            <tr><td><strong>Total Gaji Bersih</strong></td><td>Rp {{ number_format($payrolls->sum('gaji_bersih'), 0, ',', '.') }}</td></tr>
        </table>
    </div>

    <div class="footer">
        Dicetak oleh sistem &mdash; {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>