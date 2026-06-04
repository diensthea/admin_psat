<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nis')->unique();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas')->unique();
            $table->timestamps();
        });

        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('mapels', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel')->unique();
            $table->string('nama_mapel');
            $table->timestamps();
        });

        Schema::create('berita_acaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('jumlah_peserta')->default(0);
            $table->integer('hadir')->default(0);
            $table->integer('tidak_hadir')->default(0);
            $table->text('catatan')->nullable();
            $table->longText('signature_guru')->nullable();
            $table->timestamps();
        });

        Schema::create('daftar_hadirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_acara_id')->constrained('berita_acaras')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->string('status')->default('belum_hadir'); // belum_hadir, hadir, sakit, izin, alfa
            $table->longText('signature_siswa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_hadirs');
        Schema::dropIfExists('berita_acaras');
        Schema::dropIfExists('mapels');
        Schema::dropIfExists('kelas_siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('siswas');
        Schema::dropIfExists('gurus');
    }
};
