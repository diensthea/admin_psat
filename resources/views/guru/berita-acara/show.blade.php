@extends('layouts.app')

@section('title', 'Detail & Manajemen Presensi Berita Acara')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
            <a href="{{ route('guru.dashboard') }}" class="text-blue-900 hover:text-blue-800 font-bold text-sm flex items-center mb-2">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-extrabold text-blue-950">Detail & Presensi Ujian</h1>
            <p class="text-sm text-gray-500">Pantau kehadiran siswa secara real-time dan sesuaikan status kehadiran jika diperlukan.</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('guru.berita-acara.print', $beritaAcara->id) }}" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2.5 rounded-lg text-sm font-bold transition flex items-center shadow-sm">
                <i class="fa-solid fa-print mr-2 text-gray-600"></i> Cetak Laporan
            </a>
            <a href="{{ route('guru.berita-acara.edit', $beritaAcara->id) }}" class="bg-blue-900 hover:bg-blue-805 hover:bg-blue-800 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition flex items-center shadow">
                <i class="fa-solid fa-file-pen mr-2"></i> Edit Berita Acara
            </a>
        </div>
    </div>

    <!-- Stats & Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Rincian Berita Acara (Width 1/3) -->
        <div class="bg-white rounded-xl border border-gray-150 shadow-sm p-5 space-y-4">
            <h2 class="text-base font-bold text-gray-800 pb-3 border-b border-gray-100 flex items-center">
                <i class="fa-solid fa-circle-info mr-2 text-blue-900"></i> Informasi Pelaksanaan
            </h2>

            <div class="space-y-3 text-sm">
                <div>
                    <label class="text-xs font-bold text-gray-400 block uppercase">Mata Pelajaran</label>
                    <span class="font-extrabold text-gray-950">{{ $beritaAcara->mapel->nama_mapel }}</span>
                    <span class="text-xs text-blue-900 font-mono block">Kode: {{ $beritaAcara->mapel->kode_mapel }}</span>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 block uppercase">Kelas Pelaksanaan</label>
                    <span class="px-2.5 py-0.5 bg-purple-105 bg-purple-100 text-purple-800 font-bold text-xs rounded-full inline-block mt-0.5">
                        {{ $beritaAcara->kelas->nama_kelas }}
                    </span>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 block uppercase">Waktu Ujian</label>
                    <span class="font-semibold text-gray-900 block">{{ \Carbon\Carbon::parse($beritaAcara->tanggal)->translatedFormat('l, d F Y') }}</span>
                    <span class="text-xs text-gray-500 block font-mono">{{ $beritaAcara->jam_mulai }} s.d {{ $beritaAcara->jam_selesai }} WIB</span>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-400 block uppercase">Catatan Laporan</label>
                    <p class="text-xs text-gray-600 italic bg-gray-50 p-2.5 rounded border border-gray-100 mt-1 whitespace-pre-wrap leading-relaxed">
                        {{ $beritaAcara->catatan ?: '[ Tidak ada catatan khusus yang ditulis oleh Pengawas ]' }}
                    </p>
                </div>
                <div class="pt-2">
                    <label class="text-xs font-bold text-gray-400 block uppercase mb-1">Tanda Tangan Pengawas</label>
                    <div class="h-20 w-32 border border-dashed border-gray-200 rounded flex items-center justify-center bg-gray-50 overflow-hidden">
                        @if($beritaAcara->signature_guru)
                            <img src="{{ $beritaAcara->signature_guru }}" alt="Tanda Tangan Guru" class="max-h-full max-w-full object-contain">
                        @else
                            <span class="text-xs text-red-500 italic">Belum ditandatangani</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring Siswa & Statistik (Width 2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Counters -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Total Peserta</p>
                    <h4 class="text-2xl font-extrabold text-blue-900 mt-1">{{ $beritaAcara->jumlah_peserta }}</h4>
                </div>
                <div class="bg-green-50 border border-green-150 border-green-100 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Kehadiran (Siswa)</p>
                    <h4 class="text-2xl font-extrabold text-green-700 mt-1">{{ $beritaAcara->hadir }}</h4>
                </div>
                <div class="bg-red-50 border border-red-155 border-red-100 rounded-xl p-4 text-center">
                    <p class="text-xs font-bold text-red-500 uppercase tracking-wider">Absen (Siswa)</p>
                    <h4 class="text-2xl font-extrabold text-red-600 mt-1">{{ $beritaAcara->tidak_hadir }}</h4>
                </div>
            </div>

            <!-- Student List Table -->
            <div class="bg-white rounded-xl border border-gray-150 shadow-sm overflow-hidden text-sm">
                <div class="p-4 bg-gray-50 border-b flex justify-between items-center">
                    <h3 class="font-bold text-gray-800"><i class="fa-solid fa-users mr-1.5 text-blue-900"></i> Presensi Kehadiran Siswa</h3>
                    <span class="text-xxs px-2 py-0.5 bg-blue-100 text-blue-900 font-extrabold rounded-full">Situs Sinkron</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-5 py-3 text-left w-12">No</th>
                                <th class="px-5 py-3 text-left w-36">NIS</th>
                                <th class="px-5 py-3 text-left">Nama Siswa</th>
                                <th class="px-5 py-3 text-center w-40">Status</th>
                                <th class="px-5 py-3 text-center w-44">Tanda Tangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if($beritaAcara->daftarHadirs->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-400">
                                        Rombongan belajar kelas ini tidak memiliki siswa yang ditugaskan. Silakan lakukan <a href="{{ route('admin.penempatan.index') }}" class="text-blue-900 hover:underline">pemetaan kelas</a> terlebih dahulu.
                                    </td>
                                </tr>
                            @else
                                @foreach($beritaAcara->daftarHadirs->sortBy('siswa.nama') as $index => $dh)
                                    <tr class="hover:bg-gray-50 group transition">
                                        <td class="px-5 py-3 text-gray-500 text-center font-semibold">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-5 py-3 font-mono font-bold text-gray-700">
                                            {{ $dh->siswa->nis }}
                                        </td>
                                        <td class="px-5 py-3 font-semibold text-gray-900">
                                            {{ $dh->siswa->nama }}
                                        </td>
                                        <td class="px-5 py-3 text-center">
                                            <form action="{{ route('guru.berita-acara.attendance-status', $dh->id) }}" method="POST" class="inline">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()" class="w-full text-xs font-bold rounded-lg border-gray-300 bg-white p-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-center
                                                    @if($dh->status == 'hadir') text-green-700 bg-green-50 border-green-200
                                                    @elseif($dh->status == 'sakit') text-blue-700 bg-blue-50 border-blue-200
                                                    @elseif($dh->status == 'izin') text-yellow-700 bg-yellow-50 border-yellow-250
                                                    @elseif($dh->status == 'alfa') text-red-600 bg-red-50 border-red-200
                                                    @else text-gray-500 bg-gray-50 border-gray-200
                                                    @endif">
                                                    <option value="belum_hadir" {{ $dh->status == 'belum_hadir' ? 'selected' : '' }}>Belum Hadir</option>
                                                    <option value="hadir" {{ $dh->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="sakit" {{ $dh->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                    <option value="izin" {{ $dh->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="alfa" {{ $dh->status == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="px-5 py-2 whitespace-nowrap text-center align-middle">
                                            @if($dh->signature_siswa)
                                                <div class="flex justify-center">
                                                    <img src="{{ $dh->signature_siswa }}" alt="TTD Siswa" class="h-9 w-auto border border-gray-100 bg-white rounded shadow-sm hover:scale-110 transition duration-150 cursor-zoom-in" onclick="openSignatureModal('{{ $dh->siswa->nama }}', '{{ $dh->signature_siswa }}')">
                                                </div>
                                            @else
                                                <span class="text-xs text-red-500 font-semibold italic flex items-center justify-center">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full animate-ping mr-1.5"></span> Belum TTD
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SIGNATURE VIEWER MODAL -->
<div id="modalSignature" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 transition-opacity" onclick="closeSignatureModal()"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-sm w-full relative z-10 border">
            <div class="bg-gray-50 px-4 py-3 border-b flex justify-between items-center">
                <h3 class="font-bold text-gray-800 text-sm" id="modalStudentName">Tanda Tangan</h3>
                <button onclick="closeSignatureModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
            </div>
            <div class="p-6 bg-white flex justify-center">
                <div class="border rounded border-gray-200 p-2 bg-gray-50 flex items-center justify-center">
                    <img id="modalSignatureImage" src="" alt="Pratinjau Tanda Tangan" class="max-h-48 max-w-full">
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function openSignatureModal(studentName, signatureSrc) {
        document.getElementById('modalStudentName').textContent = 'Tanda Tangan: ' + studentName;
        document.getElementById('modalSignatureImage').src = signatureSrc;
        document.getElementById('modalSignature').classList.remove('hidden');
    }

    function closeSignatureModal() {
        document.getElementById('modalSignature').classList.add('hidden');
    }
</script>
@endsection
@endsection