<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'eleve_id',
        'date',
        'statut',
        'remarque',
        'emploi_du_temps_id', // ✅ ajout ici
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function emploiDuTemps()
    {
        return $this->belongsTo(EmploiDuTemps::class, 'emploi_du_temps_id');
    }

}
