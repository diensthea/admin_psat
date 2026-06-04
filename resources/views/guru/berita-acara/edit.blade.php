@extends('layouts.app')

@section('title', 'Edit Berita Acara Ujian')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('guru.dashboard') }}" class="text-blue-900 hover:text-blue-850 font-bold text-sm flex items-center mb-2">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
        <h1 class="text-2xl font-extrabold text-blue-950 flex items-center">
            <i class="fa-solid fa-file-pen mr-2 text-blue-900"></i> Edit Berita Acara
        </h1>
        <p class="text-sm text-gray-500">Ubah rincian pelaksanaan ujian Anda. Mengubah <b class="text-purple-800">Kelas</b> akan menghapus dan mereset ulang seluruh daftar hadir siswa di kelas tersebut.</p>
    </div>

    <!-- Card Form -->
    <div class="bg-white rounded-xl border border-gray-150 shadow-sm overflow-hidden">
        <form action="{{ route('guru.berita-acara.update', $beritaAcara->id) }}" method="POST" id="formBeritaAcara">
            @csrf
            
            <!-- Hidden inputs -->
            <input type="hidden" name="signature_guru" id="signature_guru">

            <div class="p-6 space-y-5">
                <!-- Grid layout for inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Mata Pelajaran <span class="text-red-550 text-red-500">*</span></label>
                        <select name="mapel_id" required class="w-full border border-gray-300 rounded-lg p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @foreach($mapels as $mapel)
                                <option value="{{ $mapel->id }}" {{ $beritaAcara->mapel_id == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }} ({{ $mapel->kode_mapel }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Kelas <span class="text-red-550 text-red-500">*</span></label>
                        <select name="kelas_id" required class="w-full border border-gray-300 rounded-lg p-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            @foreach($kelases as $kelas)
                                <option value="{{ $kelas->id }}" {{ $beritaAcara->kelas_id == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pelaksanaan <span class="text-red-550 text-red-500">*</span></label>
                        <input type="date" name="tanggal" value="{{ $beritaAcara->tanggal }}" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Mulai <span class="text-red-550 text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" value="{{ substr($beritaAcara->jam_mulai, 0, 5) }}" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Jam Selesai <span class="text-red-550 text-red-500">*</span></label>
                        <input type="time" name="jam_selesai" value="{{ substr($beritaAcara->jam_selesai, 0, 5) }}" required class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Catatan Kejadian / Kebenaran Pelaksanaan (Alternatif)</label>
                    <textarea name="catatan" rows="3" placeholder="Contoh: Pelaksanaan ujian berjalan tertib." class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">{{ $beritaAcara->catatan }}</textarea>
                </div>

                <!-- Existing Signature Display -->
                <div class="space-y-3 border-t pt-4">
                    <div class="flex items-center space-y-1 md:space-y-0 md:justify-between flex-col md:flex-row">
                        <div>
                            <label class="block text-sm font-bold text-gray-800">Tanda Tangan Digital Pengawas</label>
                            <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah tanda tangan digital saat ini.</p>
                        </div>
                        <button type="button" id="clearSignature" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded text-xs font-bold transition">
                            <i class="fa-solid fa-eraser mr-1"></i> Bersihkan Canvas Baru
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white border rounded-lg p-2.5 flex flex-col items-center justify-center">
                            <span class="text-xxs text-gray-400 mb-1 font-bold">TANDA TANGAN SAAT INI</span>
                            <div class="h-28 w-auto px-4 py-2 border rounded border-gray-100 bg-gray-50 flex items-center justify-center">
                                @if($beritaAcara->signature_guru)
                                    <img src="{{ $beritaAcara->signature_guru }}" alt="Tanda Tangan Saat Ini" class="max-h-full object-contain">
                                @else
                                    <span class="text-xs text-red-500 italic">Belum Tanda Tangan</span>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 flex flex-col items-center">
                            <span class="text-xxs text-gray-400 mb-1 font-bold">BUAT TANDA TANGAN BARU</span>
                            <div class="border border-gray-200 bg-white rounded overflow-hidden shadow-inner">
                                <canvas id="canvasSignature" width="220" height="110" class="cursor-crosshair bg-white"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit footer -->
            <div class="bg-gray-50 px-6 py-4 border-t flex justify-end space-x-3">
                <a href="{{ route('guru.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2.5 rounded-lg text-sm font-bold transition">Batal</a>
                <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-md flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('canvasSignature');
        const ctx = canvas.getContext('2d');
        const clearBtn = document.getElementById('clearSignature');
        const form = document.getElementById('formBeritaAcara');
        const signatureInput = document.getElementById('signature_guru');

        let hasNewDrawing = false;

        // Set line styles for canvas
        ctx.strokeStyle = '#1e3a8a'; // elegant blue
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        let drawing = false;

        function getMousePos(canvasDom, touchOrMouseEvent) {
            const rect = canvasDom.getBoundingClientRect();
            let clientX, clientY;

            if (touchOrMouseEvent.touches && touchOrMouseEvent.touches.length > 0) {
                clientX = touchOrMouseEvent.touches[0].clientX;
                clientY = touchOrMouseEvent.touches[0].clientY;
            } else {
                clientX = touchOrMouseEvent.clientX;
                clientY = touchOrMouseEvent.clientY;
            }

            return {
                x: clientX - rect.left,
                y: clientY - rect.top
            };
        }

        // Start drawing
        function startDrawing(e) {
            drawing = true;
            hasNewDrawing = true;
            const pos = getMousePos(canvas, e);
            ctx.beginPath();
            ctx.moveTo(pos.x, pos.y);
            e.preventDefault();
        }

        // Keep drawing
        function draw(e) {
            if (!drawing) return;
            const pos = getMousePos(canvas, e);
            ctx.lineTo(pos.x, pos.y);
            ctx.stroke();
            e.preventDefault();
        }

        // Stop drawing
        function stopDrawing(e) {
            drawing = false;
        }

        // Event listeners for Mouse
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        window.addEventListener('mouseup', stopDrawing);

        // Event listeners for Mobile Touch
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', draw, { passive: false });
        canvas.addEventListener('touchend', stopDrawing);

        // Clear Canvas
        clearBtn.addEventListener('click', function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            signatureInput.value = '';
            hasNewDrawing = false;
        });

        // Form Submit
        form.addEventListener('submit', function(e) {
            if (hasNewDrawing) {
                const blank = document.createElement('canvas');
                blank.width = canvas.width;
                blank.height = canvas.height;
                
                if (canvas.toDataURL() !== blank.toDataURL()) {
                    // Update signature field base64
                    signatureInput.value = canvas.toDataURL('image/png');
                }
            }
        });
    });
</script>
@endsection
@endsection