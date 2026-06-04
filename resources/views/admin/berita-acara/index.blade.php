@extends('layouts.app')

@section('title', 'Monitoring Berita Acara & Daftar Hadir')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div>
        <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
            <i class="fa-solid fa-file-signature mr-2 text-blue-900"></i> Monitoring Berita Acara & Kehadiran
        </h1>
        <p class="text-sm text-gray-500">Pantau seluruh laporan pelaksanaan ujian (Berita Acara) dan status tanda tangan kehadiran digital siswa.</p>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-sm">
        <div class="overflow-x-auto">
            @if($beritaAcaras->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-clipboard-list text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada Berita Acara yang diterbitkan oleh Guru Pengawas.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left w-16">No</th>
                            <th class="px-6 py-3 text-left">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left">Kelas</th>
                            <th class="px-6 py-3 text-left">Hari/Tanggal/Sesi</th>
                            <th class="px-6 py-3 text-left">Guru Pengawas</th>
                            <th class="px-6 py-3 text-center">Tanda Tangan Guru</th>
                            <th class="px-6 py-3 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($beritaAcaras as $index => $ba)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-950">{{ $ba->mapel->nama_mapel }}</div>
                                    <div class="text-xs text-blue-905 text-blue-900 font-mono">{{ $ba->mapel->kode_mapel }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold text-xs rounded-full">
                                        {{ $ba->kelas->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-gray-950">{{ \Carbon\Carbon::parse($ba->tanggal)->translatedFormat('l, d F Y') }}</div>
                                    <div class="text-xs text-gray-500">Waktu: {{ $ba->jam_mulai }} - {{ $ba->jam_selesai }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                    {{ $ba->guru->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($ba->signature_guru)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 items-center">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Ditandatangani
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 items-center">
                                            <i class="fa-solid fa-clock mr-1"></i> Menunggu TTD
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                    <a href="{{ route('admin.berita-acara.show', $ba->id) }}" 
                                       class="bg-blue-50 text-blue-700 hover:bg-blue-100 p-2 rounded-lg text-xs font-bold transition shadow-sm inline-block">
                                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Detail
                                    </a>
                                    
                                    <form action="{{ route('admin.berita-acara.destroy', $ba->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus Berita Acara dan semua Daftar Hadir terkait? Tindakan ini tidak dapat dibatalkan.')"
                                            class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-lg text-xs font-bold transition shadow-sm">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                                        </button>
                                    </form>
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