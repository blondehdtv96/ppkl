<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'siswa', 'wali_kelas', 'bp', 'kaprog', 'tu', 'hubin'])->default('siswa')->after('password');
            $table->string('kelas')->nullable()->after('role'); // untuk siswa
            $table->string('jurusan')->nullable()->after('kelas'); // untuk siswa
            $table->string('nis')->nullable()->after('jurusan'); // untuk siswa
            $table->boolean('is_active')->default(true)->after('nis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};