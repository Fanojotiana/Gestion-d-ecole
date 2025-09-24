<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'matricule',

        'grade',
        'email',
        'telephone',
        'adresse',
        'photo',
        'user_id'
    ];

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'enseignant_matiere');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function emplois()
    {
        return $this->hasMany(EmploiDuTemps::class);
    }
}
