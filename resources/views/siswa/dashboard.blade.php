@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div>
        <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
            <i class="fa-solid fa-graduation-cap mr-2 text-blue-900"></i> Portal Presensi Ujian Siswa
        </h1>
        <p class="text-sm text-gray-500">Selamat datang, <span class="font-bold text-gray-800">{{ $siswa->nama }}</span> (NIS: {{ $siswa->nis }}). Isi daftar hadir ujian Anda di bawah ini.</p>
    </div>

    <!-- Active Assessments List -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden text-sm">
        <div class="p-5 border-b border-gray-150 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-800">
                <i class="fa-solid fa-list-check mr-1.5 text-blue-900"></i> Daftar Ujian Aktif Hari Ini & Mendatang
            </h2>
            <span class="text-xxs px-2.5 py-0.5 bg-blue-105 bg-blue-100 text-blue-900 font-extrabold rounded-full">Kelas Anda</span>
        </div>

        <div class="divide-y divide-gray-100">
            @if($assessments->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <i class="fa-solid fa-clipboard-question text-5xl mb-3"></i>
                    <p class="text-sm font-medium">Tidak ada rilis mata pelajaran ujian yang terdaftar untuk kelas Anda saat ini.</p>
                    <p class="text-xs text-gray-400 mt-1">Silakan koordinasikan dengan Guru Pengawas kelas Anda.</p>
                </div>
            @else
                @foreach($assessments as $ba)
                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 hover:bg-gray-50 transition">
                        <!-- Left: Exam metadata -->
                        <div class="space-y-2">
                            <div>
                                <h3 class="text-base font-bold text-gray-900">{{ $ba->mapel->nama_mapel }}</h3>
                                <div class="flex items-center space-x-2 text-xs font-mono text-gray-500">
                                    <span>{{ $ba->mapel->kode_mapel }}</span>
                                    <span>&bull;</span>
                                    <span class="px-2 py-0.5 bg-purple-100 text-purple-800 font-bold text-xxs rounded-full">{{ $ba->kelas->nama_kelas }}</span>
                                    <span>&bull;</span>
                                    <span>Pengawas: {{ $ba->guru->nama }}</span>
                                </div>
                            </div>
                            <div class="flex items-center text-xs text-gray-600 space-x-4">
                                <span class="flex items-center"><i class="fa-solid fa-calendar mr-1.5 text-gray-400"></i> {{ \Carbon\Carbon::parse($ba->tanggal)->translatedFormat('l, d F Y') }}</span>
                                <span class="flex items-center"><i class="fa-solid fa-clock mr-1.5 text-gray-400"></i> {{ $ba->jam_mulai }} - {{ $ba->jam_selesai }} WIB</span>
                            </div>
                        </div>

                        <!-- Right: Student signature/status -->
                        <div class="flex items-center space-x-4">
                            @if($ba->student_attendance)
                                @if($ba->student_attendance->status === 'hadir')
                                    <div class="flex flex-col items-end space-y-1">
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-55 bg-green-100 text-green-850 text-green-800 items-center">
                                            <i class="fa-solid fa-circle-check mr-1"></i> Hadir & Ditandatangani
                                        </span>
                                        @if($ba->student_attendance->signature_siswa)
                                            <img src="{{ $ba->student_attendance->signature_siswa }}" alt="Tanda Tangan Anda" class="h-10 w-auto bg-gray-50 border border-gray-100 rounded p-0.5">
                                        @endif
                                    </div>
                                @elseif(in_array($ba->student_attendance->status, ['sakit', 'izin', 'alfa']))
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold uppercase
                                        @if($ba->student_attendance->status === 'sakit') bg-blue-100 text-blue-800
                                        @elseif($ba->student_attendance->status === 'izin') bg-yellow-101 bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $ba->student_attendance->status }} (Tercatat oleh Pengawas)
                                    </span>
                                @else
                                    <a href="{{ route('siswa.attendance.show', $ba->id) }}" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-md flex items-center">
                                        <i class="fa-solid fa-signature mr-2"></i> Tanda Tangan Kehadiran
                                    </a>
                                @endif
                            @else
                                <span class="text-xs text-red-500 italic font-semibold">Tabel absensi belum terhubung.</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection