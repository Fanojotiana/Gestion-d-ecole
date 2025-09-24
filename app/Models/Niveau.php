<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'classes_possibles', // Ajouté ici
    ];

    protected $casts = [
        'classes_possibles' => 'array', // Le cast JSON → tableau PHP automatique
    ];

    public function classes()
    {
        return $this->hasMany(Classe::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class);
    }
}
