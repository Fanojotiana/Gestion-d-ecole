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
        Schema::create('emploi_du_temps', function (Blueprint $table) {
            $table->id();

            // Relations
            $table->foreignId('matiere_id')->constrained('matieres')->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained('enseignants')->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained('niveaux')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->integer('semaine')->default(1); // semaine 1 par défaut

            // Planning
            $table->string('jour'); // Lundi, Mardi, etc.
            $table->time('heure_debut');
            $table->time('heure_fin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emploi_du_temps');
    }
};
