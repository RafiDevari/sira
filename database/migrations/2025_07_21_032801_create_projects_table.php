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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('id_lead');
            $table->unsignedBigInteger('id_aplikasi');
            $table->date('waktu_mulai');
            $table->date('waktu_selesai');
            $table->string('tipe');
            $table->timestamps();

            $table->foreign('id_lead')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_aplikasi')->references('id')->on('applications')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
