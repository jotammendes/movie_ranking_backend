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

    /**
     * Lista de atributos vindos de relacionamentos.
     *
     * @var array
     */
    protected $with = [
        'movies',
    ];

    public function movies() {
        return $this->belongsToMany(Movie::class);
    }
}
