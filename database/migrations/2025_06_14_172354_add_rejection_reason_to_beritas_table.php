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
        // Update tipe enum untuk status
        \DB::statement("ALTER TABLE beritas MODIFY COLUMN status ENUM('draft', 'pending', 'approved', 'rejected') DEFAULT 'pending'");
        
        // Tambahkan kolom rejection_reason
        Schema::table('beritas', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
        });
        
        // Update nilai status yang lama ke nilai yang baru
        \DB::table('beritas')
            ->where('status', 'terbit')
            ->update(['status' => 'approved']);
            
        \DB::table('beritas')
            ->where('status', 'arsip')
            ->update(['status' => 'rejected']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan nilai status ke nilai lama
        \DB::table('beritas')
            ->where('status', 'approved')
            ->update(['status' => 'terbit']);
            
        \DB::table('beritas')
            ->where('status', 'rejected')
            ->update(['status' => 'arsip']);
        
        // Hapus kolom rejection_reason
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
        
        // Kembalikan tipe enum ke nilai semula
        \DB::statement("ALTER TABLE beritas MODIFY COLUMN status ENUM('draft', 'terbit', 'arsip') DEFAULT 'draft'");
    }
};
