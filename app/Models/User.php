<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens,HasFactory, Notifiable;  // ← MUST INCLUDE HasApiTokens


   
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];


    protected $hidden = [
        'username',
        'password',
        'remember_token',
    ];

 
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
