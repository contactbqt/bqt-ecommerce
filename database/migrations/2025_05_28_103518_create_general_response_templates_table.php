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
        Schema::create('general_response_templates', function (Blueprint $table) {
            $table->id();
            $table->longText('format_structure');
            $table->tinyInteger('status')->default(1); // 0 for inactive, 1 for active
            $table->timestamps();
            $table->softDeletes(); // Adds a `deleted_at` column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_response_templates');
        $table->dropSoftDeletes(); // Removes the `deleted_at` column
    }
};
