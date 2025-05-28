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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->integer('nim')->unique();
            $table->primary('nim');
            $table->integer('jadwal_id'); 
            $table->foreign('jadwal_id')->references('id')->on('jadwal')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};