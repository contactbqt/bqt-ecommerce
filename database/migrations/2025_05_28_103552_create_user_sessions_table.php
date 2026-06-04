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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 255);
            $table->unsignedBigInteger('user_id');
            $table->text('leadership_words')->nullable();
            $table->text('narrations')->nullable();
            $table->unsignedBigInteger('general_response_template_id')->nullable();
            $table->longText('request')->nullable();
            $table->longText('response')->nullable();
            $table->timestamp('response_time')->nullable();
                       
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('general_response_template_id')->references('id')->on('general_response_templates');
            $table->timestamps();
            $table->softDeletes(); // Adds a `deleted_at` column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
        $table->dropSoftDeletes(); // Removes the `deleted_at` column
    }
};
