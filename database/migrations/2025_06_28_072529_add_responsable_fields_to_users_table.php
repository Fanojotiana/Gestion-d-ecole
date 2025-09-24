<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'date_naissance')) {
                $table->date('date_naissance')->nullable()->after('prenom');
            }
            if (!Schema::hasColumn('users', 'sexe')) {
                $table->string('sexe', 10)->nullable()->after('date_naissance');
            }
            if (!Schema::hasColumn('users', 'adresse')) {
                $table->text('adresse')->nullable()->after('sexe');
            }
            if (!Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone')->nullable()->after('adresse');
            }
            if (!Schema::hasColumn('users', 'responsable_type')) {
                $table->string('responsable_type')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'date_naissance',
                'sexe',
                'adresse',
                'telephone',
                'responsable_type',
            ]);
        });
    }
};
