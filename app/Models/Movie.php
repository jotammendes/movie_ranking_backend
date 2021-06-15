<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    /**
     * Lista de atributos.
     *
     * @var array
     */
    protected $fillable = [
        'poster_path',
        'title',
        'original_title',
        'overview',
        'release_date',
        'popularity',
        'vote_average',
    ];

    /**
     * Lista de atributos formatados.
     *
     * @var array
     */
    protected $appends = [
        'release_date_formatted',
    ];

    public function getReleaseDateFormattedAttribute() {
        return date('d/m/Y', strtotime($this->release_date));
    }

    public function genres() {
        return $this->belongsToMany(Genre::class, 'genre_movies');
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}
