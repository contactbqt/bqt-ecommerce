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
        Schema::table('attribute_categories', function (Blueprint $table) {
            $table->string('type')->default('filter')->after('category_id');
        });

        // Update existing records based on attribute properties
        // If it's only a variant, set type to 'variant'
        DB::table('attribute_categories')
            ->join('attributes', 'attribute_categories.attribute_id', '=', 'attributes.id')
            ->where('attributes.is_filter', 0)
            ->where('attributes.is_variant', 1)
            ->update(['attribute_categories.type' => 'variant']);
        
        // If it's both, we currently only have one record. 
        // We'll leave it as 'filter' for now, the user can now add it as 'variant' separately.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_categories', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
