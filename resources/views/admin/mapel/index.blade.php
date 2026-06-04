@extends('layouts.app')

@section('title', 'Manajemen Mata Pelajaran')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
                <i class="fa-solid fa-book-bookmark mr-2 text-blue-900"></i> Mata Pelajaran (Mapel)
            </h1>
            <p class="text-sm text-gray-500">Daftar mata pelajaran yang diuji pada Penilaian Sumatif Akhir Tahun.</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="toggleModal('modalImport')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i> Import CSV
            </button>
            <button onclick="toggleModal('modalAdd')" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Mapel
            </button>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden max-w-4xl mx-auto">
        <div class="overflow-x-auto">
            @if($mapels->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-book-open text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada data mata pelajaran. Silakan tambah secara manual atau import via CSV.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left w-20">No</th>
                            <th class="px-6 py-3 text-left">Kode Mapel</th>
                            <th class="px-6 py-3 text-left">Nama Mata Pelajaran</th>
                            <th class="px-6 py-3 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @foreach($mapels as $index => $mapel)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-blue-900">
                                    {{ $mapel->kode_mapel }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-950">
                                    {{ $mapel->nama_mapel }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                    <button onclick="openEditModal('{{ $mapel->id }}', '{{ $mapel->kode_mapel }}', '{{ $mapel->nama_mapel }}')" 
                                        class="bg-yellow-50 text-yellow-700 hover:bg-yellow-100 p-2 rounded-lg text-xs font-bold transition shadow-sm">
                                        <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.mapel.destroy', $mapel->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')"
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

<!-- ================= MODALS ================= -->

<!-- ADD MAPEL MODAL -->
<div id="modalAdd" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalAdd')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-md w-full relative z-10 border border-gray-150">
            <div class="bg-blue-900 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-plus-circle mr-2 text-yellow-400"></i> Tambah Mata Pelajaran</h3>
                <button onclick="toggleModal('modalAdd')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.mapel.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Mapel (Harus Unik)</label>
                        <input type="text" name="kode_mapel" placeholder="Contoh: MP-001, BINDO-XII" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" placeholder="Contoh: Bahasa Indonesia" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalAdd')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MAPEL MODAL -->
<div id="modalEdit" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalEdit')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-md w-full relative z-10 border border-gray-150">
            <div class="bg-yellow-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-pen-to-square mr-2"></i> Edit Mata Pelajaran</h3>
                <button onclick="toggleModal('modalEdit')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kode Mapel</label>
                        <input type="text" id="edit_kode_mapel" name="kode_mapel" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Mata Pelajaran</label>
                        <input type="text" id="edit_nama_mapel" name="nama_mapel" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalEdit')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-yellow-650 bg-yellow-605 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- IMPORT MAPEL MODAL -->
<div id="modalImport" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalImport')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-md w-full relative z-10 border border-gray-150">
            <div class="bg-emerald-600 px-6 py-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-file-excel mr-2 text-yellow-300"></i> Import Mapel via CSV</h3>
                <button onclick="toggleModal('modalImport')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.mapel.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-xs text-blue-900 space-y-2">
                        <p class="font-bold"><i class="fa-solid fa-circle-info"></i> Panduan format CSV:</p>
                        <p>Format CSV wajib memiliki header kolom:</p>
                        <p class="font-mono bg-blue-100 p-1 rounded text-center">kode_mapel,nama_mapel</p>
                        <ul class="list-disc list-inside">
                            <li><span class="font-bold">kode_mapel</span>: Kode mapel unik</li>
                            <li><span class="font-bold">nama_mapel</span>: Nama mata pelajaran</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File CSV</label>
                        <input type="file" name="file_csv" accept=".csv,text/csv,text/plain" required class="w-full border border-gray-300 rounded-lg p-2 sm:text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalImport')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Import</button>
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

    function openEditModal(id, kode, nama) {
        document.getElementById('edit_kode_mapel').value = kode;
        document.getElementById('edit_nama_mapel').value = nama;
        
        let actionUrl = "{{ route('admin.mapel.update', ':id') }}";
        actionUrl = actionUrl.replace(':id', id);
        document.getElementById('formEdit').action = actionUrl;
        
        toggleModal('modalEdit');
    }
</script>
@endsection
@endsection