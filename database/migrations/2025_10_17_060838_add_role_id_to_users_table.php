<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Jangan buat ulang kolom, karena sudah ada
            // Cukup tambahkan foreign key jika belum ada
            try {
                $table->foreign('role_id')
                      ->references('id')
                      ->on('roles')
                      ->onDelete('cascade');
            } catch (\Exception $e) {
                // Abaikan error jika foreign key sudah ada
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try {
                $table->dropForeign(['role_id']);
            } catch (\Exception $e) {
                // Abaikan jika foreign key sudah dihapus
            }
        });
    }
};
