<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    /**
     * Lista de atributos.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Lista de atributos que devem estar escondidos.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];
}
