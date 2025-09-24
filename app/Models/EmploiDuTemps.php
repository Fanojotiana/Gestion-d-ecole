<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    use HasFactory;

    protected $table = 'emploi_du_temps';
    protected $primaryKey = 'id';
    public $incrementing = true; // si clé auto-incrémentée
    protected $keyType = 'int';   // type clé primaire
    protected $fillable = [
        'matiere_id',
        'enseignant_id',
        'niveau_id',
        'classe_id',
        'jour',
        'semaine',
        'heure_debut',
        'heure_fin',
    ];



    public function matiere()
    {
        return $this->belongsTo(Matiere::class,'matiere_id');
    }
    public function presences()
    {
        return $this->hasMany(Presence::class, 'cours_id');
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class, 'enseignant_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }
    public function cours()
    {
        return $this->belongsTo(Cours::class, 'cours_id');
    }
}
