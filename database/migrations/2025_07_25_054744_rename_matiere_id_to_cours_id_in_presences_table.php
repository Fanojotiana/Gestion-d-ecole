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
        Schema::table('presences', function (Blueprint $table) {
            // Supprimer la clé étrangère existante si elle existe
            $table->dropForeign(['matiere_id']);

            // Renommer la colonne
            $table->renameColumn('matiere_id', 'cours_id');

            // Ajouter la nouvelle clé étrangère
            $table->foreign('cours_id')->references('id')->on('cours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presences', function (Blueprint $table) {
            // Supprimer la clé étrangère
            $table->dropForeign(['cours_id']);

            // Renommer la colonne dans l'autre sens
            $table->renameColumn('cours_id', 'matiere_id');

            // Remettre l'ancienne clé étrangère
            $table->foreign('matiere_id')->references('id')->on('matieres')->onDelete('cascade');
        });
    }
};
