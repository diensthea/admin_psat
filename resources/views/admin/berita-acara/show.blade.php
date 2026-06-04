@extends('layouts.app')

@section('title', 'Detail Berita Acara & Daftar Hadir')

@section('content')
<div class="space-y-6">
    <!-- Back Button & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 print:hidden">
        <div>
            <a href="{{ route('admin.berita-acara.index') }}" class="text-blue-900 hover:text-blue-755 hover:text-blue-800 font-bold text-sm flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Monitoring
            </a>
            <h1 class="text-2xl font-extrabold text-blue-950">Detail Pelaksanaan Ujian</h1>
        </div>
        <div>
            <button onclick="window.print()" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-print mr-2"></i> Cetak Dokumen
            </a>
        </div>
    </div>

    <!-- DOCUMENT AREA - PRINT FRIENDLY CONTAINER -->
    <div class="bg-white p-6 md:p-8 rounded-xl border border-gray-150 shadow-sm space-y-8 print:p-0 print:border-none print:shadow-none">
        
        <!-- Header Dokumen (KOP LAPORAN) -->
        <div class="text-center pb-6 border-b-2 border-double border-gray-800 space-y-1">
            <h2 class="text-xl font-extrabold tracking-wide uppercase text-gray-900">BERITA ACARA & DAFTAR HADIR</h2>
            <h3 class="text-lg font-bold uppercase text-gray-800">PENILAIAN SUMATIF AKHIR TAHUN (PSAT)</h3>
            <p class="text-sm font-semibold text-gray-600">TAHUN PELAJARAN 2025/2026</p>
        </div>

        <!-- Detail Section grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-sm text-gray-900">
            <div class="space-y-3">
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Mata Pelajaran</span>
                    <span class="flex-1 font-extrabold text-blue-950">: {{ $beritaAcara->mapel->nama_mapel }} ({{ $beritaAcara->mapel->kode_mapel }})</span>
                </div>
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Kelas</span>
                    <span class="flex-1 font-bold">: {{ $beritaAcara->kelas->nama_kelas }}</span>
                </div>
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Hari / Tanggal</span>
                    <span class="flex-1 font-semibold">: {{ \Carbon\Carbon::parse($beritaAcara->tanggal)->translatedFormat('l, d F Y') }}</span>
                </div>
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Waktu</span>
                    <span class="flex-1 font-semibold">: {{ $beritaAcara->jam_mulai }} s.d {{ $beritaAcara->jam_selesai }}</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Guru Pengawas</span>
                    <span class="flex-1 font-bold">: {{ $beritaAcara->guru->nama }}</span>
                </div>
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Jumlah Peserta</span>
                    <span class="flex-1 font-semibold">: {{ $beritaAcara->jumlah_peserta }} Siswa</span>
                </div>
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Hadir / Absen</span>
                    <span class="flex-1">: 
                        <span class="text-green-700 font-bold">{{ $beritaAcara->hadir }} Hadir</span>, 
                        <span class="text-red-650 text-red-650 bg-red-50 px-1.5 py-0.5 rounded text-red-600 font-bold">{{ $beritaAcara->tidak_hadir }} Absen</span>
                    </span>
                </div>
                <div class="flex border-b border-gray-100 pb-1.5">
                    <span class="w-36 font-bold text-gray-500">Status Laporan</span>
                    <span class="flex-1 font-bold text-emerald-600">: Selesai Pelaksanaan</span>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Hadir Siswa -->
        <div>
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center print:text-sm">
                <i class="fa-solid fa-list-check mr-2 text-blue-900 print:hidden"></i> DAFTAR HADIR DAN TANDA TANGAN SISWA DIGITAL
            </h3>
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-600 uppercase">
                        <tr>
                            <th class="px-4 py-3 text-left w-12 border-r">No</th>
                            <th class="px-4 py-3 text-left w-36 border-r">NIS</th>
                            <th class="px-4 py-3 text-left border-r">Nama Siswa</th>
                            <th class="px-4 py-3 text-center w-36 border-r">Status Kehadiran</th>
                            <th class="px-4 py-3 text-center w-56">Tanda Tangan Digital</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($beritaAcara->daftarHadirs->isEmpty())
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                    Tidak ada data siswa terdaftar di kehadiran ini.
                                </td>
                            </tr>
                        @else
                            @foreach($beritaAcara->daftarHadirs->sortBy('siswa.nama') as $index => $dh)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-center border-r font-semibold text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border-r font-mono text-gray-700 font-semibold">{{ $dh->siswa->nis }}</td>
                                    <td class="px-4 py-3 border-r font-bold text-gray-900">{{ $dh->siswa->nama }}</td>
                                    <td class="px-4 py-3 text-center border-r">
                                        @if($dh->status == 'hadir')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-green-100 text-green-850 text-green-800">Hadir</span>
                                        @elseif($dh->status == 'sakit')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-blue-105 bg-blue-100 text-blue-800">Sakit</span>
                                        @elseif($dh->status == 'izin')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-yellow-100 text-yellow-800">Izin</span>
                                        @elseif($dh->status == 'alfa')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-red-105 bg-red-100 text-red-800">Alfa</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase bg-gray-100 text-gray-500">Belum Hadir</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center align-middle">
                                        @if($dh->signature_siswa)
                                            <div class="flex justify-center">
                                                <img src="{{ $dh->signature_siswa }}" alt="Tanda Tangan Siswa" class="h-12 w-auto bg-white border border-gray-100 rounded-sm">
                                            </div>
                                        @else
                                            <span class="text-xs text-red-500 italic font-semibold">Belum Tanda Tangan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Catatan dan Kolom Pengesahan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm text-gray-900 pt-4">
            <!-- Catatan Pengawas -->
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 space-y-2">
                <h4 class="font-bold text-gray-800"><i class="fa-solid fa-note-sticky mr-1"></i> Catatan Pelaksanaan / Pengawas:</h4>
                <p class="text-xs text-gray-700 italic leading-relaxed whitespace-pre-wrap">
                    {{ $beritaAcara->catatan ?: 'Pelaksanaan ujian berjalan tertib, aman, dan kondusif tanpa kendala atau hambatan tertentu.' }}
                </p>
            </div>

            <!-- Tanda Tangan Pengawas -->
            <div class="flex flex-col items-center justify-end text-center space-y-1">
                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($beritaAcara->tanggal)->translatedFormat('d F Y') }}</p>
                <p class="font-bold text-gray-800">Guru Pengawas,</p>
                
                <div class="h-28 w-44 flex items-center justify-center border border-dashed border-gray-200 rounded bg-white my-1 overflow-hidden">
                    @if($beritaAcara->signature_guru)
                        <img src="{{ $beritaAcara->signature_guru }}" alt="Tanda Tangan Guru" class="max-h-full max-w-full object-contain">
                    @else
                        <span class="text-xs text-red-500 font-semibold italic">Belum Tanda Tangan</span>
                    @endif
                </div>

                <p class="font-bold text-gray-900 underline">{{ $beritaAcara->guru->nama }}</p>
                <p class="text-xs text-gray-500 font-mono">NIP. {{ $beritaAcara->guru->nip }}</p>
            </div>
        </div>

        <!-- Footer TTD Kepsek / Panitia Default for Print -->
        <div class="hidden print:grid grid-cols-2 gap-8 text-sm text-gray-900 pt-12">
            <div class="text-center">
                <p class="font-semibold text-gray-800">Mengetahui,</p>
                <p class="font-bold text-gray-900">Ketua Panitia PSAT,</p>
                <div class="h-20"></div>
                <p class="font-bold text-gray-900 underline">________________________</p>
                <p class="text-xs text-gray-500">NIP. .........................</p>
            </div>
            <div class="text-center">
                <p class="font-semibold text-gray-800">Mengesahkan,</p>
                <p class="font-bold text-gray-900">Kepala Sekolah,</p>
                <div class="h-20"></div>
                <p class="font-bold text-gray-950 underline">H. Ahmad Fauzi, M.Pd.</p>
                <p class="text-xs text-gray-500">NIP. 197405232002121003</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body {
        background-color: white !important;
        color: black !important;
    }
    header, nav, sidebar, footer, .sidebar, .navbar, .print\:hidden, [class*="print:hidden"] {
        display: none !important;
    }
    .wrapper, .content, main {
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection