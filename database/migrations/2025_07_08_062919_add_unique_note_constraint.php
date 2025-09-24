<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->unique(['eleve_id', 'matiere_id', 'periode'], 'unique_note_by_eleve_matiere_periode');
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropUnique('unique_note_by_eleve_matiere_periode');
        });
    }
};
