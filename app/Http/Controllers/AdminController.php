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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // === DASHBOARD ===
    public function dashboard()
    {
        $stats = [
            'guru' => Guru::count(),
            'siswa' => Siswa::count(),
            'kelas' => Kelas::count(),
            'mapel' => Mapel::count(),
            'berita_acara' => BeritaAcara::count(),
        ];

        $recentBeritaAcara = BeritaAcara::with(['kelas', 'mapel', 'guru'])
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBeritaAcara'));
    }

    // === GURU MANAGEMENT ===
    public function guruIndex()
    {
        $gurus = Guru::with('user')->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function guruStore(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|unique:gurus,nip',
            'nama' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->nip,
                'email' => $request->email ?: ($request->nip . '@psat.com'),
                'password' => Hash::make($request->password),
                'role' => 'guru',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => $request->nip,
                'nama' => $request->nama,
            ]);
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil ditambahkan.');
    }

    public function guruUpdate(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $request->validate([
            'nip' => 'required|string|unique:gurus,nip,' . $guru->id,
            'nama' => 'required|string',
            'email' => 'nullable|email|unique:users,email,' . ($guru->user_id ?: 0),
            'password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $guru->update([
                'nip' => $request->nip,
                'nama' => $request->nama,
            ]);

            if ($guru->user) {
                $userData = [
                    'name' => $request->nama,
                    'username' => $request->nip,
                ];

                if ($request->email) {
                    $userData['email'] = $request->email;
                }

                if ($request->password) {
                    $userData['password'] = Hash::make($request->password);
                }

                $guru->user->update($userData);
            }
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil diubah.');
    }

    public function guruDestroy($id)
    {
        $guru = Guru::findOrFail($id);
        DB::transaction(function () use ($guru) {
            if ($guru->user) {
                $guru->user->delete();
            }
            $guru->delete();
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data Guru berhasil dihapus.');
    }

    public function guruResetPassword(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        if ($guru->user) {
            $guru->user->update([
                'password' => Hash::make($guru->nip),
            ]);
            return redirect()->route('admin.guru.index')->with('success', 'Password Guru reset ke default (NIP).');
        }
        return redirect()->route('admin.guru.index')->with('error', 'Guru tidak memiliki userID.');
    }

    public function guruImport(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        $header = fgetcsv($handle, 1000, ',');
        
        $count = 0;
        DB::transaction(function () use ($handle, &$count) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 2) continue;
                $nip = trim($row[0]);
                $nama = trim($row[1]);
                $email = isset($row[2]) ? trim($row[2]) : null;
                $password = isset($row[3]) && trim($row[3]) !== '' ? trim($row[3]) : $nip;

                if (empty($nip) || empty($nama)) continue;

                $user = User::updateOrCreate(
                    ['username' => $nip],
                    [
                        'name' => $nama,
                        'email' => $email ?: ($nip . '@psat.com'),
                        'password' => Hash::make($password),
                        'role' => 'guru',
                    ]
                );

                Guru::updateOrCreate(
                    ['nip' => $nip],
                    [
                        'user_id' => $user->id,
                        'nama' => $nama,
                    ]
                );
                $count++;
            }
        });
        fclose($handle);

        return redirect()->route('admin.guru.index')->with('success', $count . ' data Guru berhasil diimport.');
    }

    // === SISWA MANAGEMENT ===
    public function siswaIndex()
    {
        $siswas = Siswa::with('user')->get();
        return view('admin.siswa.index', compact('siswas'));
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'nis' => 'required|string|unique:siswas,nis',
            'nama' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->nis,
                'email' => $request->email ?: ($request->nis . '@psat.com'),
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nama' => $request->nama,
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    public function siswaUpdate(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $request->validate([
            'nis' => 'required|string|unique:siswas,nis,' . $siswa->id,
            'nama' => 'required|string',
            'email' => 'nullable|email|unique:users,email,' . ($siswa->user_id ?: 0),
            'password' => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $siswa->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
            ]);

            if ($siswa->user) {
                $userData = [
                    'name' => $request->nama,
                    'username' => $request->nis,
                ];

                if ($request->email) {
                    $userData['email'] = $request->email;
                }

                if ($request->password) {
                    $userData['password'] = Hash::make($request->password);
                }

                $siswa->user->update($userData);
            }
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil diubah.');
    }

    public function siswaDestroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        DB::transaction(function () use ($siswa) {
            if ($siswa->user) {
                $siswa->user->delete();
            }
            $siswa->delete();
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data Siswa berhasil dihapus.');
    }

    public function siswaResetPassword(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        if ($siswa->user) {
            $siswa->user->update([
                'password' => Hash::make($siswa->nis),
            ]);
            return redirect()->route('admin.siswa.index')->with('success', 'Password Siswa reset ke default (NIS).');
        }
        return redirect()->route('admin.siswa.index')->with('error', 'Siswa tidak memiliki userID.');
    }

    public function siswaImport(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        
        $header = fgetcsv($handle, 1000, ',');
        $count = 0;

        DB::transaction(function () use ($handle, &$count) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 2) continue;
                $nis = trim($row[0]);
                $nama = trim($row[1]);
                $email = isset($row[2]) ? trim($row[2]) : null;
                $password = isset($row[3]) && trim($row[3]) !== '' ? trim($row[3]) : $nis;

                if (empty($nis) || empty($nama)) continue;

                $user = User::updateOrCreate(
                    ['username' => $nis],
                    [
                        'name' => $nama,
                        'email' => $email ?: ($nis . '@psat.com'),
                        'password' => Hash::make($password),
                        'role' => 'siswa',
                    ]
                );

                Siswa::updateOrCreate(
                    ['nis' => $nis],
                    [
                        'user_id' => $user->id,
                        'nama' => $nama,
                    ]
                );
                $count++;
            }
        });
        fclose($handle);

        return redirect()->route('admin.siswa.index')->with('success', $count . ' data Siswa berhasil diimport.');
    }

    // === KELAS MANAGEMENT ===
    public function kelasIndex()
    {
        $kelases = Kelas::withCount('siswas')->get();
        return view('admin.kelas.index', compact('kelases'));
    }

    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas',
        ]);

        Kelas::create($request->only('nama_kelas'));

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function kelasUpdate(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $request->validate([
            'nama_kelas' => 'required|string|unique:kelas,nama_kelas,' . $kelas->id,
        ]);

        $kelas->update($request->only('nama_kelas'));

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diupdate.');
    }

    public function kelasDestroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    public function kelasImport(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');
        $count = 0;

        DB::transaction(function () use ($handle, &$count) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (empty($row) || trim($row[0]) === '') continue;
                $nama_kelas = trim($row[0]);

                Kelas::firstOrCreate(['nama_kelas' => $nama_kelas]);
                $count++;
            }
        });
        fclose($handle);

        return redirect()->route('admin.kelas.index')->with('success', $count . ' Kelas berhasil diimport.');
    }

    // === PENEMPATAN SISWA ===
    public function penempatanIndex()
    {
        $kelases = Kelas::orderBy('nama_kelas')->get();
        $siswas = Siswa::orderBy('nama')->get();
        
        $placements = DB::table('kelas_siswa')
            ->join('kelas', 'kelas_siswa.kelas_id', '=', 'kelas.id')
            ->join('siswas', 'kelas_siswa.siswa_id', '=', 'siswas.id')
            ->select('kelas.nama_kelas', 'kelas.id as kelas_id', 'siswas.nama as nama_siswa', 'siswas.nis', 'siswas.id as siswa_id')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('siswas.nama')
            ->get();

        return view('admin.penempatan.index', compact('kelases', 'siswas', 'placements'));
    }

    public function penempatanStore(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|exists:siswas,id',
        ]);

        $exists = DB::table('kelas_siswa')
            ->where('kelas_id', $request->kelas_id)
            ->where('siswa_id', $request->siswa_id)
            ->exists();

        if ($exists) {
            return redirect()->route('admin.penempatan.index')->with('error', 'Siswa sudah ditempatkan di kelas ini.');
        }

        DB::table('kelas_siswa')->insert([
            'kelas_id' => $request->kelas_id,
            'siswa_id' => $request->siswa_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.penempatan.index')->with('success', 'Siswa berhasil ditempatkan ke Kelas.');
    }

    public function penempatanDestroy($kelas_id, $siswa_id)
    {
        DB::table('kelas_siswa')
            ->where('kelas_id', $kelas_id)
            ->where('siswa_id', $siswa_id)
            ->delete();

        return redirect()->route('admin.penempatan.index')->with('success', 'Siswa berhasil dikeluarkan dari Kelas.');
    }

    public function penempatanImport(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');
        $count = 0;

        DB::transaction(function () use ($handle, &$count) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 2) continue;
                $nama_kelas = trim($row[0]);
                $nis = trim($row[1]);

                if (empty($nama_kelas) || empty($nis)) continue;

                $kelas = Kelas::where('nama_kelas', $nama_kelas)->first();
                $siswa = Siswa::where('nis', $nis)->first();

                if ($kelas && $siswa) {
                    $exists = DB::table('kelas_siswa')
                        ->where('kelas_id', $kelas->id)
                        ->where('siswa_id', $siswa->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('kelas_siswa')->insert([
                            'kelas_id' => $kelas->id,
                            'siswa_id' => $siswa->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $count++;
                    }
                }
            }
        });
        fclose($handle);

        return redirect()->route('admin.penempatan.index')->with('success', $count . ' penempatan Siswa berhasil diimport.');
    }

    // === MAPEL MANAGEMENT ===
    public function mapelIndex()
    {
        $mapels = Mapel::all();
        return view('admin.mapel.index', compact('mapels'));
    }

    public function mapelStore(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|unique:mapels,kode_mapel',
            'nama_mapel' => 'required|string',
        ]);

        Mapel::create($request->only('kode_mapel', 'nama_mapel'));

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    public function mapelUpdate(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        $request->validate([
            'kode_mapel' => 'required|string|unique:mapels,kode_mapel,' . $mapel->id,
            'nama_mapel' => 'required|string',
        ]);

        $mapel->update($request->only('kode_mapel', 'nama_mapel'));

        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil diupdate.');
    }

    public function mapelDestroy($id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();
        return redirect()->route('admin.mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus.');
    }

    public function mapelImport(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle, 1000, ',');
        $count = 0;

        DB::transaction(function () use ($handle, &$count) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (count($row) < 2) continue;
                $kode_mapel = trim($row[0]);
                $nama_mapel = trim($row[1]);

                if (empty($kode_mapel) || empty($nama_mapel)) continue;

                Mapel::updateOrCreate(
                    ['kode_mapel' => $kode_mapel],
                    ['nama_mapel' => $nama_mapel]
                );
                $count++;
            }
        });
        fclose($handle);

        return redirect()->route('admin.mapel.index')->with('success', $count . ' Mata Pelajaran berhasil diimport.');
    }

    // === MONITORING BERITA ACARA & DAFTAR HADIR ===
    public function beritaAcaraIndex()
    {
        $beritaAcaras = BeritaAcara::with(['kelas', 'mapel', 'guru'])
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.berita-acara.index', compact('beritaAcaras'));
    }

    public function beritaAcaraShow($id)
    {
        $beritaAcara = BeritaAcara::with(['kelas', 'mapel', 'guru', 'daftarHadirs.siswa'])->findOrFail($id);
        return view('admin.berita-acara.show', compact('beritaAcara'));
    }

    public function beritaAcaraDestroy($id)
    {
        $beritaAcara = BeritaAcara::findOrFail($id);
        $beritaAcara->delete();
        return redirect()->route('admin.berita-acara.index')->with('success', 'Berita Acara & Daftar Hadir terkait berhasil dihapus.');
    }
}
