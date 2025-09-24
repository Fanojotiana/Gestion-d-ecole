<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    use HasFactory;
    protected $fillable = ['nom'];

    public function niveaux()
    {
        return $this->belongsToMany(Niveau::class);
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'enseignant_matiere');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
    // public function niveau()
    // {
    //     return $this->belongsTo(Niveau::class);
    // }
    // app/Models/Matiere.php

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
