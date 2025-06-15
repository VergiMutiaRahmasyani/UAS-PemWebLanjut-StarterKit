<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInBeritasTable extends Migration
{
    public function up()
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change(); // Ubah ke VARCHAR(20) atau sesuai kebutuhan
            // Atau jika menggunakan ENUM:
            // $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('status', 10)->default('pending')->change(); // Kembali ke kondisi awal jika perlu rollback
        });
    }
}