<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // This is a helper table used in Category::getFormattedCategory() method
        // It's used to generate sequential numbers for complex hierarchical queries
        Schema::create('incr_mst', function (Blueprint $table) {
            $table->id();
            // No timestamps needed for this helper table
        });
        
        // Populate with sequential numbers (1-1000)
        // This provides a sequence for the SUBSTRING_INDEX operations
        for ($i = 1; $i <= 1000; $i++) {
            DB::table('incr_mst')->insert(['id' => $i]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incr_mst');
    }
};
