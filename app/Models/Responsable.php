<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'responsable_type',
        // autres champs spécifiques si tu en ajoutes
    ];

    // Relation vers User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
