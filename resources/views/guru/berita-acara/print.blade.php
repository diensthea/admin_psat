<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Berita Acara & Daftar Hadir PSAT</title>
    <!-- Google Fonts for Print elegance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif, system-ui, sans-serif;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 20px;
            font-size: 13pt;
            line-height: 1.4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* Kop Surat / Kepala Dokumen */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
        }
        .kop-surat h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0 0 4px 0;
        }
        .kop-surat p {
            font-size: 11pt;
            margin: 0;
            font-weight: normal;
            font-style: italic;
        }

        .judul-halaman {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            text-decoration: underline;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        /* Detail Metadata */
        .meta-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .meta-row {
            display: table-row;
        }
        .meta-cell-label {
            display: table-cell;
            width: 180px;
            padding: 4px 0;
            font-weight: bold;
        }
        .meta-cell-value {
            display: table-cell;
            padding: 4px 0;
        }

        /* Tabel Presensi */
        .table-hadir {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }
        .table-hadir th, .table-hadir td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }
        .table-hadir th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        .table-hadir td.center {
            text-align: center;
        }
        .table-hadir td.monospace {
            font-family: monospace;
            font-size: 10.5pt;
        }
        .signature-img {
            max-height: 42px;
            max-width: 120px;
            display: block;
            margin: 0 auto;
        }

        /* Catatan dan Kolom Pengesahan TTD */
        .catatan-container {
            border: 1px solid #000;
            padding: 10px 15px;
            margin-bottom: 35px;
            background-color: #fafafa;
        }
        .catatan-title {
            font-weight: bold;
            font-size: 11pt;
            margin-top: 0;
            margin-bottom: 6px;
            text-decoration: underline;
        }
        .catatan-text {
            font-size: 11pt;
            margin: 0;
            font-style: italic;
        }

        .ttd-container {
            display: table;
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .ttd-col {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .ttd-space {
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .ttd-space img {
            max-height: 80px;
            max-width: 150px;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin: 0;
        }
        .ttd-sub {
            font-size: 11pt;
            margin: 2px 0 0 0;
        }

        /* Page Break Rules for Print */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4;
                margin: 20mm;
            }
        }

        /* Top Action Bar when viewed in browser */
        .no-print-bar {
            background-color: #1e3a8a;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            border-bottom: 2px solid #172554;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .no-print-bar button {
            background-color: #eab308;
            color: #1e3a8a;
            border: none;
            padding: 8px 16px;
            font-weight: 800;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .no-print-bar button:hover {
            background-color: #facc15;
            transform: translateY(-1px);
        }
        .no-print-bar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }
        .no-print-bar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Browser Action Bar -->
    <div class="no-print-bar no-print">
        <div>
            <a href="javascript:window.close();" onclick="window.history.back(); return false;">&larr; Kembali ke Dashboard</a>
        </div>
        <div style="font-weight: bold;">PRATINJAU CETAK RESMI</div>
        <div>
            <button onclick="window.print()">CETAK DOKUMEN</button>
        </div>
    </div>

    <!-- Official PDF Container -->
    <div class="container" style="margin-top: 30px;">
        
        <!-- KOP MANDATORY -->
        <div class="kop-surat">
            <h1>PANITIA PENILAIAN SUMATIF AKHIR TAHUN (PSAT)</h1>
            <h2>SMKN 1 GARUT</h2>
            <p>Alamat: Jl. Cimanuk No. 309 A</p>
        </div>

        <div class="judul-halaman">
            BERITA ACARA & DAFTAR HADIR PESERTA PSAT
        </div>

        <!-- Metadata -->
        <div class="meta-grid">
            <div class="meta-row">
                <div class="meta-cell-label">Mata Pelajaran</div>
                <div class="meta-cell-value">: {{ $beritaAcara->mapel->nama_mapel }} ({{ $beritaAcara->mapel->kode_mapel }})</div>
            </div>
            <div class="meta-row">
                <div class="meta-cell-label">Kelas Pelaksanaan</div>
                <div class="meta-cell-value">: {{ $beritaAcara->kelas->nama_kelas }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-cell-label">Hari / Tanggal</div>
                <div class="meta-cell-value">: {{ \Carbon\Carbon::parse($beritaAcara->tanggal)->translatedFormat('l, d F Y') }}</div>
            </div>
            <div class="meta-row">
                <div class="meta-cell-label">Jam Pelaksanaan</div>
                <div class="meta-cell-value">: {{ $beritaAcara->jam_mulai }} s.d {{ $beritaAcara->jam_selesai }} WIB</div>
            </div>
            <div class="meta-row">
                <div class="meta-cell-label font-bold">Guru Pengawas</div>
                <div class="meta-cell-value">: <strong>{{ $beritaAcara->guru->nama }}</strong></div>
            </div>
        </div>

        <!-- Presensi Peserta -->
        <table class="table-hadir">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 15%">NIS</th>
                    <th>Nama Lengkap Peserta Didik</th>
                    <th style="width: 18%">Status Kehadiran</th>
                    <th style="width: 25%">Tanda Tangan Digital</th>
                </tr>
            </thead>
            <tbody>
                @if($beritaAcara->daftarHadirs->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; color: #777;">Tidak ada peserta/siswa terdaftar.</td>
                    </tr>
                @else
                    @foreach($beritaAcara->daftarHadirs->sortBy('siswa.nama') as $index => $dh)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td class="monospace center">{{ $dh->siswa->nis }}</td>
                            <td><strong>{{ $dh->siswa->nama }}</strong></td>
                            <td class="center">
                                @if($dh->status == 'hadir')
                                    HADIR
                                @elseif($dh->status == 'sakit')
                                    SAKIT
                                @elseif($dh->status == 'izin')
                                    IZIN
                                @elseif($dh->status == 'alfa')
                                    ALFA
                                @else
                                    BELUM HADIR
                                @endif
                            </td>
                            <td>
                                @if($dh->signature_siswa)
                                    <img src="{{ $dh->signature_siswa }}" alt="Tanda Tangan" class="signature-img">
                                @else
                                    <div style="text-align: center; color: #cc0000; font-size: 9.5pt; font-style: italic;">Tidak TTD</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Catatan Real-time -->
        <div class="catatan-container">
            <h4 class="catatan-title">CATATAN KHUSUS PELAKSANAAN:</h4>
            <p class="catatan-text">
                {{ $beritaAcara->catatan ?: 'Ujian terlaksana dengan baik, tertib, dan lancar. Seluruh peserta mematuhi tata tertib ujian yang dipersyaratkan oleh Panitia PSAT.' }}
            </p>
        </div>

        <!-- Kolom Pengesahan Tanda Tangan -->
        <div class="ttd-container">
            <div class="ttd-col">
                <p>Mengetahui,</p>
                <p style="font-weight: bold; margin-top: 2px;">Kepala Sekolah</p>
                <div class="ttd-space">
                    <!-- Blank for physical hand sign of principal, or styled signature -->
                </div>
                <p class="ttd-nama">Dr. H. Asep Rudiana, M.Pd.</p>
                <p class="ttd-sub">NIP. 197009051997021001</p>
            </div>
            
            <div class="ttd-col">
                <p>{{ \Carbon\Carbon::parse($beritaAcara->tanggal)->translatedFormat('d F Y') }}</p>
                <p style="font-weight: bold; margin-top: 2px;">Guru Pengawas Ujian</p>
                <div class="ttd-space">
                    @if($beritaAcara->signature_guru)
                        <img src="{{ $beritaAcara->signature_guru }}" alt="TTD Guru">
                    @endif
                </div>
                <p class="ttd-nama">{{ $beritaAcara->guru->nama }}</p>
                <p class="ttd-sub">NIP. {{ $beritaAcara->guru->nip }}</p>
            </div>
        </div>

    </div>

    <!-- Automatically trigger browser print dialog -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Wait for images to load, then open print dialog
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>