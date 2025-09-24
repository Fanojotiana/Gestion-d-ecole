<?php

// database/migrations/xxxx_xx_xx_create_notes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('eleve_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->float('note'); // sur 20
            $table->float('coefficient')->default(1);
            $table->enum('periode', ['Trimestre 1', 'Trimestre 2', 'Trimestre 3']);
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
