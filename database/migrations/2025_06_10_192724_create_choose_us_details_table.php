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
        Schema::create('choose_us_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('choose_us_id')->nullable();
            $table->string('sub_heading')->nullable();
            $table->string('sub_summary')->nullable();
            $table->string('sub_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choose_us_details');
    }
};
