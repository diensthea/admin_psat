<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Siswa;
use App\Models\BeritaAcara;
use App\Models\DaftarHadir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaDashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Profil Siswa tidak ditemukan.');
        }

        // Get student's class (or classes)
        $kelasIds = $siswa->kelas()->pluck('kelas.id')->toArray();

        // Get all Berita Acara for student's classes
        // and link student's current attendance status
        $assessments = BeritaAcara::whereIn('kelas_id', $kelasIds)
            ->with(['kelas', 'mapel', 'guru'])
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($ba) use ($siswa) {
                // Find matching attendance record
                $attendance = DaftarHadir::where('berita_acara_id', $ba->id)
                    ->where('siswa_id', $siswa->id)
                    ->first();
                
                $ba->student_attendance = $attendance;
                return $ba;
            });

        return view('siswa.dashboard', compact('siswa', 'assessments'));
    }

    public function showAttendanceForm($berita_acara_id)
    {
        $siswa = Auth::user()->siswa;
        $beritaAcara = BeritaAcara::with(['kelas', 'mapel', 'guru'])->findOrFail($berita_acara_id);

        // Check if student is in this class
        $isPlaced = DB::table('kelas_siswa')
            ->where('kelas_id', $beritaAcara->kelas_id)
            ->where('siswa_id', $siswa->id)
            ->exists();

        if (!$isPlaced) {
            abort(403, 'Anda tidak terdaftar di kelas untuk ujian ini.');
        }

        $attendance = DaftarHadir::where('berita_acara_id', $beritaAcara->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if (!$attendance) {
            abort(404, 'Daftar hadir tidak ditemukan.');
        }

        if ($attendance->status === 'hadir') {
            return redirect()->route('siswa.dashboard')->with('success', 'Anda sudah menandatangani daftar hadir ujian ini.');
        }

        return view('siswa.attendance.sign', compact('beritaAcara', 'attendance'));
    }

    public function signAttendance(Request $request, $berita_acara_id)
    {
        $request->validate([
            'signature_siswa' => 'required|string',
        ]);

        $siswa = Auth::user()->siswa;
        $beritaAcara = BeritaAcara::findOrFail($berita_acara_id);

        $attendance = DaftarHadir::where('berita_acara_id', $beritaAcara->id)
            ->where('siswa_id', $siswa->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $attendance, $beritaAcara) {
            $attendance->update([
                'status' => 'hadir',
                'signature_siswa' => $request->signature_siswa,
            ]);

            // Recalculate attendance stats on Berita Acara
            $hadirCount = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->where('status', 'hadir')->count();
            $jumlahPeserta = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->count();

            $beritaAcara->update([
                'hadir' => $hadirCount,
                'tidak_hadir' => $jumlahPeserta - $hadirCount,
            ]);
        });

        return redirect()->route('siswa.dashboard')->with('success', 'Daftar hadir berhasil ditandatangani. Terima kasih!');
    }
}
