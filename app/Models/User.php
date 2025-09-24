<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'pseudo',
        'email',
        'password',
        'photo',
        'role',             // ex: 'admin', 'enseignant', 'responsable', 'user'
        'date_naissance',
        'sexe',
        'adresse',
        'telephone',
        'responsable_type', // si tu différencies types de responsables
    ];

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
        // ou belongsTo(Enseignant::class) selon ta structure
    }


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
