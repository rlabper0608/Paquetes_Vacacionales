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
        Schema::create('vacacion', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 60)->unique();
            $table->text('descripcion');
            $table->decimal('precio', 8,2);
            $table->foreignId('idtipo');
            $table->timestamps();

            $table->foreign('idtipo')->references('id')->on('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacacion');
    }
};
