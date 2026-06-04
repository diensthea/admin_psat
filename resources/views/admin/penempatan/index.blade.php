@extends('layouts.app')

@section('title', 'Penempatan Siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
                <i class="fa-solid fa-people-arrows mr-2 text-blue-900"></i> Penempatan Siswa ke Kelas
            </h1>
            <p class="text-sm text-gray-500">Petakan siswa ke kelas masing-masing agar dapat terdaftar di berita acara & presensi.</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="toggleModal('modalImport')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i> Import CSV Penempatan
            </button>
            <button onclick="toggleModal('modalAdd')" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Tambah Penempatan
            </button>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($placements->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-people-line text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada penempatan kelas kelas siswa. Silakan hubungkan siswa ke kelas.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left w-20">No</th>
                            <th class="px-6 py-3 text-left">Nama Kelas</th>
                            <th class="px-6 py-3 text-left">NIS (Siswa)</th>
                            <th class="px-6 py-3 text-left">Nama Lengkap Siswa</th>
                            <th class="px-6 py-3 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @foreach($placements as $index => $place)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-purple-100 text-purple-800 font-bold text-xs rounded-full">
                                        {{ $place->nama_kelas }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-blue-900 font-mono font-bold">
                                    {{ $place->nis }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-950 font-semibold">
                                    {{ $place->nama_siswa }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <form action="{{ route('admin.penempatan.destroy', [$place->kelas_id, $place->siswa_id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Keluarkan siswa ini dari kelas?')"
                                            class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-lg text-xs font-bold transition shadow-sm">
                                            <i class="fa-solid fa-rectangle-xmark mr-1"></i> Keluarkan
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

<!-- ================= MODALS ================= -->

<!-- ADD PLACEMENT MODAL -->
<div id="modalAdd" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalAdd')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-lg w-full relative z-10 border border-gray-150">
            <div class="bg-blue-900 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-user-plus mr-2 text-yellow-400"></i> Buat Penempatan Kelas</h3>
                <button onclick="toggleModal('modalAdd')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.penempatan.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                        <select name="kelas_id" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelases as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Siswa</label>
                        <select name="siswa_id" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-white">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswas as $siswa)
                                <option value="{{ $siswa->id }}">{{ $siswa->nis }} - {{ $siswa->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalAdd')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Tempatkan Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- IMPORT PLACEMENT MODAL -->
<div id="modalImport" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalImport')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-lg w-full relative z-10 border border-gray-150">
            <div class="bg-emerald-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-file-excel mr-2 text-yellow-300"></i> Import Penempatan via CSV</h3>
                <button onclick="toggleModal('modalImport')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.penempatan.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-xs text-blue-900 space-y-2">
                        <p class="font-bold"><i class="fa-solid fa-circle-info"></i> Panduan format CSV:</p>
                        <p>Format CSV penempatan wajib memiliki header kolom:</p>
                        <p class="font-mono bg-blue-100 p-1.5 rounded text-center">nama_kelas,nis</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><span class="font-bold">nama_kelas</span>: Nama kelas yang cocok di program (Contoh: XII mipa 1)</li>
                            <li><span class="font-bold">nis</span>: NIS Siswa yang sudah terdaftar di sistem</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File CSV</label>
                        <input type="file" name="file_csv" accept=".csv,text/csv,text/plain" required class="w-full border border-gray-300 rounded-lg p-2 sm:text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalImport')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Import Penempatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
@endsection