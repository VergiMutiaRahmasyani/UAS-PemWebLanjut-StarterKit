<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('beritas', 'views')) {
            Schema::table('beritas', function (Blueprint $table) {
                $table->unsignedBigInteger('views')->default(0)->after('rejection_reason');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('beritas', 'views')) {
            Schema::table('beritas', function (Blueprint $table) {
                $table->dropColumn('views');
            });
        }
    }
};