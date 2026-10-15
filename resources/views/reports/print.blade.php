<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Pelanggaran Mahasiswa - SIPMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            padding: 30px;
        }
        .header-letter {
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        .table th, .table td {
            font-size: 0.85rem;
            padding: 6px 8px;
            vertical-align: middle;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print mb-4 d-flex justify-content-between align-items-center bg-light p-3 border rounded">
        <div>
            <strong>Mode Cetak Rekapitulasi SIPMA</strong> &mdash; Silakan klik tombol cetak atau simpan sebagai PDF.
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary font-weight-bold">
                Cetak Dokumen / Simpan PDF
            </button>
            <button onclick="window.close()" class="btn btn-secondary font-weight-bold ml-2">
                Tutup
            </button>
        </div>
    </div>

    <!-- Kop Surat -->
    <div class="header-letter">
        <h4 class="font-weight-bold mb-0 text-uppercase">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h4>
        <h3 class="font-weight-bold mb-0 text-uppercase">UNIVERSITAS TEKNOLOGI KAMPUS</h3>
        <h5 class="mb-1 text-uppercase">BAGIAN KEMAHASISWAAN DAN KEDISIPLINAN (SIPMA)</h5>
        <p class="small mb-0">Jl. Kampus Terpadu No. 1 &bull; Telp: (021) 12345678 &bull; Email: kemahasiswaan@kampus.ac.id</p>
    </div>

    <div class="text-center mb-4">
        <h5 class="font-weight-bold text-uppercase mb-1" style="text-decoration: underline;">REKAPITULASI PELANGGARAN MAHASISWA & TINDAK LANJUT</h5>
        <div class="small">Tanggal Cetak: {{ date('d F Y') }} | Dicetak Oleh: {{ $user->name }} ({{ $user->role_title }})</div>
    </div>

    <table class="table table-bordered mb-4">
        <thead class="thead-light text-center">
            <tr>
                <th width="4%">No</th>
                <th width="14%">No. Laporan</th>
                <th width="12%">Tgl Kejadian</th>
                <th width="18%">Mahasiswa Terlapor</th>
                <th width="16%">Jurusan Terkait</th>
                <th width="10%">Kategori</th>
                <th width="12%">Status Kasus</th>
                <th width="14%">Sanksi Ditetapkan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $rep)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-weight-bold">{{ $rep->report_number }}</td>
                <td>{{ $rep->incident_date ? $rep->incident_date->format('d/m/Y') : '-' }}</td>
                <td>
                    <strong>{{ $rep->student_name }}</strong><br>
                    <small>NIM: {{ $rep->student_nim }}</small>
                </td>
                <td>{{ $rep->department_name }}</td>
                <td class="text-center">{{ $rep->severity ?? 'Umum' }}</td>
                <td class="text-center">{{ $rep->status_label }}</td>
                <td>{{ $rep->sanction_type ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-3">Tidak ada data laporan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row mt-5 pt-3">
        <div class="col-6 text-center">
            <p class="mb-5">Mengetahui,<br><strong>Wakil Rektor Bidang Kemahasiswaan</strong></p>
            <br><br>
            <p class="font-weight-bold mb-0">Prof. Dr. Suharto, M.Sc.</p>
            <p class="small text-muted">NIP. 196805121993031002</p>
        </div>
        <div class="col-6 text-center">
            <p class="mb-5">Kepala Bagian Kemahasiswaan,<br><strong>Universitas Teknologi Kampus</strong></p>
            <br><br>
            <p class="font-weight-bold mb-0">Dra. Siti Rahma, M.Pd.</p>
            <p class="small text-muted">NIP. 197409182001122001</p>
        </div>
    </div>
</body>
</html>
