@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
                <i class="fa-solid fa-chalkboard-user mr-2 text-blue-900"></i> Dashboard Guru Pengawas
            </h1>
            <p class="text-sm text-gray-500">Selamat datang, <span class="font-bold text-gray-800">{{ $guru->nama }}</span>. Kelola dan buat laporan Berita Acara ujian hari ini.</p>
        </div>
        <div>
            <a href="{{ route('guru.berita-acara.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow flex items-center inline-block">
                <i class="fa-solid fa-file-pen mr-2"></i> Buat Berita Acara Baru
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-900 to-indigo-900 p-5 rounded-2xl text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-blue-200">Total Berita Acara</p>
                <h3 class="text-3xl font-extrabold mt-1">{{ $beritaAcaras->count() }}</h3>
            </div>
            <div class="p-3 bg-white bg-opacity-10 rounded-xl text-3xl">
                <i class="fa-solid fa-briefcase"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-600 to-teal-600 p-5 rounded-2xl text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-200">Ujian Telah Dimulai</p>
                <h3 class="text-3xl font-extrabold mt-1">
                    {{ $beritaAcaras->where('tanggal', date('Y-m-d'))->count() }}
                </h3>
            </div>
            <div class="p-3 bg-white bg-opacity-10 rounded-xl text-3xl">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-700 to-fuchsia-700 p-5 rounded-2xl text-white shadow-md flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-purple-200">NIP Pengawas</p>
                <h3 class="text-xl font-extrabold mt-2 font-mono">{{ $guru->nip }}</h3>
            </div>
            <div class="p-3 bg-white bg-opacity-10 rounded-xl text-3xl">
                <i class="fa-solid fa-id-card"></i>
            </div>
        </div>
    </div>

    <!-- List Berita Acara Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mt-6 text-sm">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-800">
                <i class="fa-solid fa-clock-rotate-left mr-1.5 text-blue-900"></i> Riwayat Pelaksanaan Laporan Ujian
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            @if($beritaAcaras->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <i class="fa-solid fa-clipboard-question text-5xl mb-3"></i>
                    <p class="text-sm font-medium">Anda belum pernah membuat Berita Acara ujian.</p>
                    <p class="text-xs text-gray-400 mt-1">Klik tombol "Buat Berita Acara Baru" di kanan atas untuk memulai.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left w-16">No</th>
                            <th class="px-6 py-3 text-left">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left">Kelas</th>
                            <th class="px-6 py-3 text-left">Hari & Tanggal</th>
                            <th class="px-6 py-3 text-left">Sesi / Jam</th>
                            <th class="px-6 py-3 text-center">Kehadiran Siswa</th>
                            <th class="px-6 py-3 text-center w-56">Kontrol / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($beritaAcaras as $index => $ba)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">{{ $ba->mapel->nama_mapel }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $ba->mapel->kode_mapel }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-850 text-purple-800 font-bold text-xs rounded-full">
                                        {{ $ba->kelas->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($ba->tanggal)->translatedFormat('l, d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-mono text-gray-900">{{ $ba->jam_mulai }} - {{ $ba->jam_selesai }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <span class="px-2.5 py-0.5 bg-green-50 text-green-700 font-bold text-xs rounded border border-green-200">
                                            {{ $ba->hadir }} Hadir
                                        </span>
                                        <span class="px-2.5 py-0.5 bg-red-50 text-red-650 text-red-600 font-bold text-xs rounded border border-red-200">
                                            {{ $ba->tidak_hadir }} Absen
                                        </span>
                                    </div>
                                    <div class="text-xxs text-gray-400 mt-1 font-semibold">Total: {{ $ba->jumlah_peserta }} Siswa</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <a href="{{ route('guru.berita-acara.show', $ba->id) }}" 
                                            class="bg-blue-50 text-blue-700 hover:bg-blue-100 p-2 rounded-lg text-xs font-bold transition shadow-sm"
                                            title="Pantau Kehadiran / Tanda Tangan">
                                            <i class="fa-solid fa-list-check mr-1"></i> Kehadiran
                                        </a>
                                        <a href="{{ route('guru.berita-acara.edit', $ba->id) }}" 
                                            class="bg-yellow-50 text-yellow-800 hover:bg-yellow-105 hover:bg-yellow-100 p-2 rounded-lg text-xs font-bold transition shadow-sm"
                                            title="Ubah Rincian Berita Acara">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="{{ route('guru.berita-acara.print', $ba->id) }}" target="_blank"
                                            class="bg-gray-50 text-gray-700 hover:bg-gray-150 hover:bg-gray-100 p-2 rounded-lg text-xs font-bold transition shadow-sm"
                                            title="Cetak Berita Acara">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection