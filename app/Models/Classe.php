<?php

// app/Models/Classe.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'niveau_id'];

    public function niveau()
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }
    // public function eleves()
    // {
    //     return $this->belongsTo(eleve::class);
    // }
    public function eleves()
    {
        return $this->hasMany(Eleve::class, 'classe_id');
    }
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
}
