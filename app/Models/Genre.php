<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;

    /**
     * Lista de atributos.
     *
     * @var array
     */
    protected $fillable = [
        'id_tmdb',
        'title',
    ];

    public function movies() {
        return $this->belongsToMany(Movie::class, 'genre_movies');
    }
}
