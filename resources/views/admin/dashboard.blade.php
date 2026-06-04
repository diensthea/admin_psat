@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-900 to-blue-700 text-white rounded-2xl p-6 shadow-md md:p-8">
        <h1 class="text-2xl md:text-3xl font-extrabold mb-1">Selamat Datang di Portal Admin</h1>
        <p class="text-blue-100 text-sm md:text-base">Kelola seluruh parameter pengujian, darta guru, siswa, kelas, penempatan siswa, mata pelajaran, serta pantau berita acara pelaksanaan PSAT secara real-time.</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Guru Stat -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Total Guru</p>
                <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $stats['guru'] }}</h3>
            </div>
            <div class="bg-blue-50 text-blue-900 p-4 rounded-full">
                <i class="fa-solid fa-chalkboard-user text-2xl"></i>
            </div>
        </div>

        <!-- Siswa Stat -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Total Siswa</p>
                <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $stats['siswa'] }}</h3>
            </div>
            <div class="bg-green-50 text-green-700 p-4 rounded-full">
                <i class="fa-solid fa-user-graduate text-2xl"></i>
            </div>
        </div>

        <!-- Kelas Stat -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Total Kelas</p>
                <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $stats['kelas'] }}</h3>
            </div>
            <div class="bg-purple-50 text-purple-700 p-4 rounded-full">
                <i class="fa-solid fa-school text-2xl"></i>
            </div>
        </div>

        <!-- Mapel Stat -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</p>
                <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $stats['mapel'] }}</h3>
            </div>
            <div class="bg-yellow-50 text-yellow-600 p-4 rounded-full">
                <i class="fa-solid fa-book-bookmark text-2xl"></i>
            </div>
        </div>

        <!-- Berita Acara Stat -->
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Berita Acara</p>
                <h3 class="text-3xl font-extrabold text-blue-900 mt-1">{{ $stats['berita_acara'] }}</h3>
            </div>
            <div class="bg-red-50 text-red-600 p-4 rounded-full">
                <i class="fa-solid fa-file-invoice text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Quick Navigation Links (Admin Panel) -->
    <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-bold text-gray-800 mb-4"><i class="fa-solid fa-gears mr-2 text-blue-950"></i>Navigasi Administrasi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('admin.guru.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 border border-gray-100 text-center block transition group">
                <i class="fa-solid fa-chalkboard-user text-xl text-blue-900 mb-2 block group-hover:scale-110 transition"></i>
                <span class="text-sm font-bold text-gray-700">Data Guru</span>
            </a>
            <a href="{{ route('admin.siswa.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 border border-gray-100 text-center block transition group">
                <i class="fa-solid fa-user-graduate text-xl text-green-700 mb-2 block group-hover:scale-110 transition"></i>
                <span class="text-sm font-bold text-gray-700">Data Siswa</span>
            </a>
            <a href="{{ route('admin.kelas.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 border border-gray-100 text-center block transition group">
                <i class="fa-solid fa-school text-xl text-purple-700 mb-2 block group-hover:scale-110 transition"></i>
                <span class="text-sm font-bold text-gray-700">Data Kelas</span>
            </a>
            <a href="{{ route('admin.penempatan.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 border border-gray-100 text-center block transition group">
                <i class="fa-solid fa-people-arrows text-xl text-orange-600 mb-2 block group-hover:scale-110 transition"></i>
                <span class="text-sm font-bold text-gray-700">Penempatan Kelas</span>
            </a>
            <a href="{{ route('admin.mapel.index') }}" class="p-4 bg-gray-50 rounded-lg hover:bg-blue-50 border border-gray-100 text-center block transition group">
                <i class="fa-solid fa-book-open text-xl text-yellow-600 mb-2 block group-hover:scale-110 transition"></i>
                <span class="text-sm font-bold text-gray-700">Mata Pelajaran</span>
            </a>
        </div>
    </div>

    <!-- Recent Berita Acara (Monitoring) -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fa-solid fa-file-signature text-blue-900 mr-2"></i> Berita Acara Terbaru
            </h2>
            <a href="{{ route('admin.berita-acara.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-bold">
                Semua Berita Acara <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="p-0 overflow-x-auto">
            @if($recentBeritaAcara->isEmpty())
                <div class="text-center py-10 text-gray-400">
                    <i class="fa-solid fa-circle-info text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada berita acara pelaksanaan yang dibuat oleh guru.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">Tanggal</th>
                            <th class="px-6 py-3 text-left">Waktu</th>
                            <th class="px-6 py-3 text-left">Guru</th>
                            <th class="px-6 py-3 text-left">Kelas</th>
                            <th class="px-6 py-3 text-left">Mapel</th>
                            <th class="px-6 py-3 text-center">Kehadiran</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @foreach($recentBeritaAcara as $ba)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($ba->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ date('H:i', strtotime($ba->jam_mulai)) }} - {{ date('H:i', strtotime($ba->jam_selesai)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">
                                    {{ $ba->guru->nama ?? 'Guru tidak ditemukan' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">
                                        {{ $ba->kelas->nama_kelas ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    {{ $ba->mapel->nama_mapel ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="font-bold text-blue-900">{{ $ba->hadir }}</span> / <span class="text-gray-400">{{ $ba->jumlah_peserta }}</span>
                                    <div class="w-24 bg-gray-200 h-1.5 rounded-full mx-auto mt-1 overflow-hidden">
                                        @php
                                            $percent = $ba->jumlah_peserta > 0 ? ($ba->hadir / $ba->jumlah_peserta) * 100 : 0;
                                        @endphp
                                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('admin.berita-acara.show', $ba->id) }}" class="bg-blue-50 text-blue-900 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold inline-flex items-center transition shadow-sm">
                                        <i class="fa-solid fa-eye mr-1.5"></i> Detail
                                    </a>
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