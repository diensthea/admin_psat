@extends('layouts.app')

@section('title', 'Manajemen Guru')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
                <i class="fa-solid fa-chalkboard-user mr-2 text-blue-900"></i> Data Guru Pengawas
            </h1>
            <p class="text-sm text-gray-500">Daftar guru yang bertugas mengawas dan membuat berita acara ujian PSAT.</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="toggleModal('modalImport')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-file-excel mr-2"></i> Import CSV
            </button>
            <button onclick="openAddModal()" class="bg-blue-900 hover:bg-blue-800 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow flex items-center">
                <i class="fa-solid fa-user-plus mr-2"></i> Tambah Guru
            </button>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            @if($gurus->isEmpty())
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-chalkboard-user text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada data guru. Silakan tambah secara manual atau import via CSV.</p>
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">No</th>
                            <th class="px-6 py-3 text-left">NIP (Username)</th>
                            <th class="px-6 py-3 text-left">Nama Lengkap</th>
                            <th class="px-6 py-3 text-left">Email Akun</th>
                            <th class="px-6 py-3 text-center">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm">
                        @foreach($gurus as $index => $guru)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-semibold">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-blue-900">
                                    {{ $guru->nip }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-semibold">
                                    {{ $guru->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $guru->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                                    <button onclick="openEditModal('{{ $guru->id }}', '{{ $guru->nip }}', '{{ $guru->nama }}', '{{ $guru->user->email ?? '' }}')" 
                                        class="bg-yellow-50 text-yellow-700 hover:bg-yellow-100 p-2 rounded-lg text-xs font-bold transition shadow-sm" title="Edit Guru">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    
                                    <form action="{{ route('admin.guru.reset-password', $guru->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mereset password guru ini ke default (NIP)?')"
                                            class="bg-blue-50 text-blue-700 hover:bg-blue-100 p-2 rounded-lg text-xs font-bold transition shadow-sm" title="Reset Password ke NIP">
                                            <i class="fa-solid fa-key"></i> Reset
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Menghapus data Guru juga akan menghapus akun terkait. Lanjutkan?')"
                                            class="bg-red-50 text-red-600 hover:bg-red-100 p-2 rounded-lg text-xs font-bold transition shadow-sm" title="Hapus Guru">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
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

<!-- ADD GURU MODAL -->
<div id="modalAdd" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalAdd')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-lg w-full relative z-10 border border-gray-150">
            <div class="bg-blue-905 px-6 py-4 bg-blue-900 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-user-plus mr-2 text-yellow-400"></i> Tambah Data Guru</h3>
                <button onclick="toggleModal('modalAdd')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.guru.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIP (Sekaligus Username Login)</label>
                        <input type="text" name="nip" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Opsional)</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="guru@psat.com">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" required value="password123" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="text-xs text-gray-400 mt-1">Default password diset: password123</p>
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

<!-- EDIT GURU MODAL -->
<div id="modalEdit" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalEdit')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-lg w-full relative z-10 border border-gray-150">
            <div class="bg-yellow-501 px-6 py-4 bg-yellow-600 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-user-pen mr-2"></i> Edit Data Guru</h3>
                <button onclick="toggleModal('modalEdit')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">NIP (Sekaligus Username Login)</label>
                        <input type="text" id="edit_nip" name="nip" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="edit_nama" name="nama" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Opsional)</label>
                        <input type="email" id="edit_email" name="email" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Password Baru (Biarkan kosong jika tidak ingin merubah)</label>
                        <input type="password" name="password" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan password baru">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalEdit')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- IMPORT GURU MODAL -->
<div id="modalImport" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="toggleModal('modalImport')"></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-lg w-full relative z-10 border border-gray-150">
            <div class="bg-emerald-601 px-6 py-4 bg-emerald-600 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold"><i class="fa-solid fa-file-excel mr-2 text-yellow-300"></i> Import Data Guru via CSV</h3>
                <button onclick="toggleModal('modalImport')" class="text-white hover:text-gray-200 text-xl font-bold">&times;</button>
            </div>
            <form action="{{ route('admin.guru.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-xs text-blue-900 space-y-2">
                        <p class="font-bold"><i class="fa-solid fa-circle-info"></i> Panduan Format File CSV:</p>
                        <p>File wajib berupa file CSV (Comma Separated Values) dengan kriteria header kolom:</p>
                        <p class="font-mono bg-blue-100 p-1.5 rounded text-center">nip,nama,email,password</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li><span class="font-bold">nip</span>: NIP guru (Harus unik)</li>
                            <li><span class="font-bold">nama</span>: Nama lengkap guru</li>
                            <li><span class="font-bold">email</span>: (opsional) email gratis guru</li>
                            <li><span class="font-bold">password</span>: (opsional) password akun. Apabila kosong, password otomatis diset sama dengan NIP.</li>
                        </ul>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File CSV</label>
                        <input type="file" name="file_csv" accept=".csv,text/csv,text/plain" required class="w-full border border-gray-300 rounded-lg p-2 sm:text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('modalImport')" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-bold transition">Batal</button>
                    <button type="submit" class="bg-emerald-605 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow">Mulai Import</button>
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

    function openAddModal() {
        toggleModal('modalAdd');
    }

    function openEditModal(id, nip, nama, email) {
        document.getElementById('edit_nip').value = nip;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_email').value = email;
        
        let actionUrl = "{{ route('admin.guru.update', ':id') }}";
        actionUrl = actionUrl.replace(':id', id);
        document.getElementById('formEdit').action = actionUrl;
        
        toggleModal('modalEdit');
    }
</script>
@endsection
@endsection