<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\GenreMovie;

class GenreMovieController extends Controller
{
    /**
     * Função Construtora.
     */
    public function __construct(Genre $genres, Movie $movies, GenreMovie $genre_movies)
    {
        $this->genres = $genres;
        $this->movies = $movies;
        $this->genre_movies = $genre_movies;
    }

    /**
     * Função responsável relacionar filmes e gêneros.
     *
     * @param $movie_api $movie_id
     * @return mix null \\ Movie $movie
     */
    public function storeNewGenreMovie($movie_api, Movie $movie) {
        try {
            foreach($movie_api->genre_ids as $genre_id_tmdb) {
                // encontra gênero no banco
                $genre = $this->genres->where('id_tmdb', $genre_id_tmdb)->first();

                // cadastra linha de relação entre filme e gênero
                $movie->genres()->attach($genre->id);
            }

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }
}
