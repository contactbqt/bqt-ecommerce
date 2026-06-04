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
        Schema::create('attribute_value_categories', function (Blueprint $table) {
            $table->id();
            $table->integer('attribute_category_id')->nullable();
            $table->integer('attribute_value_name_category')->nullable()->comment('This is FK to attribute_values id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_value_categories');
    }
};
