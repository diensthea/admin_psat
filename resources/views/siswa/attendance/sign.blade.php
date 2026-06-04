@extends('layouts.app')

@section('title', 'Tanda Tangan Kehadiran Ujian')

@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('siswa.dashboard') }}" class="text-blue-900 hover:text-blue-800 font-bold text-sm flex items-center mb-2">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Portal
        </a>
        <h1 class="text-2xl font-extrabold text-blue-950">Bubuhkan Tanda Tangan Kehadiran</h1>
        <p class="text-sm text-gray-500">Konfirmasi kehadiran Anda pada ujian hari ini dengan menuliskan tanda tangan digital di bawah ini.</p>
    </div>

    <!-- Metadata Card -->
    <div class="bg-blue-50 border border-blue-150 border-blue-100 rounded-xl p-5 text-sm space-y-2 text-blue-900">
        <h3 class="font-extrabold text-base">{{ $beritaAcara->mapel->nama_mapel }}</h3>
        <p class="font-mono text-xs">Mata Pelajaran: {{ $beritaAcara->mapel->kode_mapel }} &bull; Kelas: {{ $beritaAcara->kelas->nama_kelas }}</p>
        <p class="text-xs">
            <i class="fa-solid fa-chalkboard-user mr-1 text-blue-400"></i> Guru Pengawas: <span class="font-bold text-gray-800">{{ $beritaAcara->guru->nama }}</span>
        </p>
    </div>

    <!-- Form Drawing Sheet -->
    <div class="bg-white rounded-xl border border-gray-150 shadow-sm overflow-hidden">
        <form action="{{ route('siswa.attendance.sign', $beritaAcara->id) }}" method="POST" id="formSignature">
            @csrf
            
            <!-- Base64 payload field -->
            <input type="hidden" name="signature_siswa" id="signature_siswa" required>

            <div class="p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <label class="block text-sm font-bold text-gray-800 uppercase tracking-wide">
                        Siswa Hand-Drawing Canvas <span class="text-red-500">*</span>
                    </label>
                    <button type="button" id="clearSignature" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1 rounded text-xs font-bold transition">
                        <i class="fa-solid fa-eraser mr-1"></i> Bersihkan
                    </button>
                </div>

                <div class="bg-gray-50 border border-gray-300 rounded-lg p-3.5 flex flex-col items-center">
                    <p class="text-xxs text-gray-400 mb-2 font-bold tracking-wide">GORES TANDA TANGAN ANDA PADA BAGIAN KOSONG DIBAWAH INI</p>
                    <div class="border border-gray-250 bg-white rounded shadow-inner overflow-hidden max-w-full">
                        <!-- Canvas is sized appropriately; responsive handle logic in JS allows mobile draw -->
                        <canvas id="canvasSignature" width="440" height="200" class="cursor-crosshair max-w-full bg-white"></canvas>
                    </div>
                </div>

                <div class="bg-amber-50 rounded border border-amber-100 p-3 text-xxs text-amber-900 leading-relaxed font-semibold">
                    <i class="fa-solid fa-circle-exclamation mr-1.5 text-amber-600"></i> 
                    PERHATIAN: Pastikan coretan tanda tangan jelas, terbaca, dan menyerupai tanda tangan kartu ujian milik Anda. Tindakan pemalsuan presensi dapat dikenai sanksi akademik!
                </div>
            </div>

            <!-- Submit buttons -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                <a href="{{ route('siswa.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-805 text-gray-800 px-5 py-2.5 rounded-lg text-sm font-bold transition">Kembali</a>
                <button type="submit" class="bg-green-700 hover:bg-green-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-md flex items-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> Kirim Kehadiran Digital
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
        const form = document.getElementById('formSignature');
        const signatureInput = document.getElementById('signature_siswa');

        // Set line styles for canvas
        ctx.strokeStyle = '#047857'; // custom emerald theme for students
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        let drawing = false;

        function getMousePos(canvasDom, touchOrMouseEvent) {
            const rect = canvasDom.getBoundingClientRect();
            let clientX, clientY;

            // Handle touch vs mouse coordinates
            if (touchOrMouseEvent.touches && touchOrMouseEvent.touches.length > 0) {
                clientX = touchOrMouseEvent.touches[0].clientX;
                clientY = touchOrMouseEvent.touches[0].clientY;
            } else {
                clientX = touchOrMouseEvent.clientX;
                clientY = touchOrMouseEvent.clientY;
            }

            // Map and scale coordinate elements
            const scaleX = canvasDom.width / rect.width;
            const scaleY = canvasDom.height / rect.height;

            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }

        // Start drawing
        function startDrawing(e) {
            drawing = true;
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
        });

        // Form Submit handler
        form.addEventListener('submit', function(e) {
            // Validate signature is painted
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            
            if (canvas.toDataURL() === blank.toDataURL()) {
                alert("Harap bubuhkan tanda tangan fisik Anda pada panel canvas yang disediakan.");
                e.preventDefault();
                return false;
            }

            // Put image base64 data to target input
            signatureInput.value = canvas.toDataURL('image/png');
        });
    });
</script>
@endsection
@endsection