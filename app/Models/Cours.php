<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'matiere_id',
        'classe_id',
        'enseignant_id'
    ];

    // Relation vers la matière
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    // Relation vers la classe
    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    // Relation vers l'enseignant
    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
    public function emploiDuTemps()
    {
        return $this->belongsTo(EmploiDuTemps::class, 'emploi_du_temps_id');
    }
    public function eleves()
    {
        return $this->belongsToMany(Eleve::class, 'cours_eleves', 'cours_id', 'eleve_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }
}
