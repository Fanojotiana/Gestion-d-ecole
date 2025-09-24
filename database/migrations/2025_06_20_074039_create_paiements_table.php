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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eleve_id');
            $table->decimal('montant', 10, 2);
            $table->date('date_paiement');
            $table->string('mois');
            $table->string('recu_pdf')->nullable(); // chemin vers le fichier PDF
            $table->timestamps();

            $table->foreign('eleve_id')->references('id')->on('eleves')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
