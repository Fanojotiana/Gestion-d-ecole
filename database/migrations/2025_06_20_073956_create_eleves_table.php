<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->unique();
            $table->string('genre');
            $table->date('date_naissance')->nullable();
            $table->string('adresse')->nullable();
            $table->string('photo')->nullable();

            // Relations
            $table->foreignId('classe_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->foreignId('niveau_id')->constrained('niveaux')->cascadeOnDelete();

            // Champ texte renommé directement
            $table->string('classe_nom')->default('Non défini');

            // Urgence
            $table->string('urgence_contact')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
