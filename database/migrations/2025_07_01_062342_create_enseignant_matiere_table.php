<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enseignant_matiere', function (Blueprint $table) {
            // Pas besoin de colonne id dans une table pivot standard
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');

            // Clé primaire composée pour éviter les doublons
            $table->primary(['enseignant_id', 'matiere_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enseignant_matiere');
    }
};
