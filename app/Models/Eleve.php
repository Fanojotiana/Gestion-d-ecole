<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'genre',
        'date_naissance',
        'adresse',
        'niveau_id',
        'classe_id',
        'urgence_contact',
        'photo',
    ];
    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
    // app/Models/Eleve.php

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_eleves', 'eleve_id', 'cours_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    protected $appends = [];
    protected $hidden = ['classe']; // cache la colonne string pour éviter la confusion

}
