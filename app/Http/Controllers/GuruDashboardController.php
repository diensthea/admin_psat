<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\BeritaAcara;
use App\Models\DaftarHadir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        
        if (!$guru) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Profil Guru tidak ditemukan.');
        }

        $beritaAcaras = BeritaAcara::where('guru_id', $guru->id)
            ->with(['kelas', 'mapel'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('guru.dashboard', compact('guru', 'beritaAcaras'));
    }

    public function createBeritaAcara()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        return view('guru.berita-acara.create', compact('kelases', 'mapels'));
    }

    public function storeBeritaAcara(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'catatan' => 'nullable|string',
            'signature_guru' => 'required|string', // Hidden canvas base64 image data
        ]);

        $guru = Auth::user()->guru;

        // Count students in selected class
        $studentsCount = DB::table('kelas_siswa')->where('kelas_id', $request->kelas_id)->count();

        DB::transaction(function () use ($request, $guru, $studentsCount) {
            $beritaAcara = BeritaAcara::create([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'guru_id' => $guru->id,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'jumlah_peserta' => $studentsCount,
                'hadir' => 0,
                'tidak_hadir' => $studentsCount,
                'catatan' => $request->catatan,
                'signature_guru' => $request->signature_guru,
            ]);

            // Automatically generate attendance list for all students in this class
            $studentPlacements = DB::table('kelas_siswa')
                ->where('kelas_id', $request->kelas_id)
                ->get();

            foreach ($studentPlacements as $placement) {
                DaftarHadir::create([
                    'berita_acara_id' => $beritaAcara->id,
                    'siswa_id' => $placement->siswa_id,
                    'status' => 'belum_hadir',
                    'signature_siswa' => null,
                ]);
            }
        });

        return redirect()->route('guru.dashboard')->with('success', 'Berita Acara berhasil dibuat. Siswa di kelas tersebut sekarang dapat menandatangani daftar hadir.');
    }

    public function showBeritaAcara($id)
    {
        $guru = Auth::user()->guru;
        $beritaAcara = BeritaAcara::where('guru_id', $guru->id)
            ->with(['kelas', 'mapel', 'daftarHadirs.siswa'])
            ->findOrFail($id);

        return view('guru.berita-acara.show', compact('beritaAcara'));
    }

    public function editBeritaAcara($id)
    {
        $guru = Auth::user()->guru;
        $beritaAcara = BeritaAcara::where('guru_id', $guru->id)->findOrFail($id);
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();

        return view('guru.berita-acara.edit', compact('beritaAcara', 'kelases', 'mapels'));
    }

    public function updateBeritaAcara(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mapels,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'catatan' => 'nullable|string',
            'signature_guru' => 'nullable|string', // Optional to re-sign
        ]);

        $guru = Auth::user()->guru;
        $beritaAcara = BeritaAcara::where('guru_id', $guru->id)->findOrFail($id);

        DB::transaction(function () use ($request, $beritaAcara) {
            $data = [
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'tanggal' => $request->tanggal,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'catatan' => $request->catatan,
            ];

            if ($request->filled('signature_guru')) {
                $data['signature_guru'] = $request->signature_guru;
            }

            // If class changed, regenerate student list
            if ($beritaAcara->kelas_id != $request->kelas_id) {
                // Delete old attendance
                DaftarHadir::where('berita_acara_id', $beritaAcara->id)->delete();

                // Get new counts
                $studentsCount = DB::table('kelas_siswa')->where('kelas_id', $request->kelas_id)->count();
                $data['jumlah_peserta'] = $studentsCount;
                $data['hadir'] = 0;
                $data['tidak_hadir'] = $studentsCount;

                // Create new basic ones
                $studentPlacements = DB::table('kelas_siswa')
                    ->where('kelas_id', $request->kelas_id)
                    ->get();

                foreach ($studentPlacements as $placement) {
                    DaftarHadir::create([
                        'berita_acara_id' => $beritaAcara->id,
                        'siswa_id' => $placement->siswa_id,
                        'status' => 'belum_hadir',
                    ]);
                }
            } else {
                // Just update counts based on current status
                $hadir = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->where('status', 'hadir')->count();
                $tidakHadir = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->where('status', '!=', 'hadir')->count();
                $data['hadir'] = $hadir;
                $data['tidak_hadir'] = $tidakHadir;
                $data['jumlah_peserta'] = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->count();
            }

            $beritaAcara->update($data);
        });

        return redirect()->route('guru.dashboard')->with('success', 'Berita Acara berhasil diupdate.');
    }

    public function printBeritaAcara($id)
    {
        $beritaAcara = BeritaAcara::with(['kelas', 'mapel', 'guru', 'daftarHadirs.siswa'])
            ->findOrFail($id);
        return view('guru.berita-acara.print', compact('beritaAcara'));
    }

    public function updateAttendanceStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:belum_hadir,hadir,sakit,izin,alfa',
        ]);

        $dh = DaftarHadir::findOrFail($id);
        $beritaAcara = $dh->beritaAcara;

        // Ensure current teacher owns this berita_acara
        if ($beritaAcara->guru_id != Auth::user()->guru->id) {
            abort(403);
        }

        DB::transaction(function () use ($dh, $beritaAcara, $request) {
            $updateData = [
                'status' => $request->status,
            ];

            if ($request->status != 'hadir') {
                $updateData['signature_siswa'] = null;
            }

            $dh->update($updateData);

            // Recalculate stats
            $hadirCount = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->where('status', 'hadir')->count();
            $jumlahPeserta = DaftarHadir::where('berita_acara_id', $beritaAcara->id)->count();

            $beritaAcara->update([
                'hadir' => $hadirCount,
                'tidak_hadir' => $jumlahPeserta - $hadirCount,
            ]);
        });

        return back()->with('success', 'Status Kehadiran siswa berhasil diperbarui.');
    }
}
