<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Função Construtora.
     */
    public function __construct(TMDBController $tmdb, GenreMovieController $genre_movies, Movie $movies)
    {
        $this->tmdb = $tmdb;
        $this->movies = $movies;
        $this->genre_movies = $genre_movies;
    }

    /**
     * Função responsável por comparar os filmes presentes
     * no banco de dados com as vindos da API.
     *
     * @return json(array, code)
     */
    public function verifyMoviesFromTMDB() {
        try {
            // recupera lista de filmes vindos da requisição
            $movies_api = $this->tmdb->getTopRatedMovies();

            foreach($movies_api as $movie_api) {
                // procura no banco pelo filme vindo da requisição
                $movie = $this->movies->where('title', $movie_api->title)->first();

                if(!$movie) { // caso não encontre, será cadastrado novo filme
                    $movie = $this->storeNewMovie($movie_api);

                    // relacionando filmes e gêneros
                    $movie = $this->genre_movies->storeNewGenreMovie($movie_api, $movie);
                }
                else { // caso encontre, o filme será atualizado
                    $movie = $this->updateMovie($movie, $movie_api);
                }
                // caso uma das ações de cadastrar/atualizar tenha dado algum erro, será retornado uma mensagem
                if(!$movie) {
                    return response()->json(["message" => "Um erro ocorreu ao cadastrar/atualizar um filme."], 404);
                }
            }

            // retorno com mensagem de êxito
            return response()->json(["message" => "Filmes verificados com sucesso."], 200);
        } catch(\Exception $e) {
            return response()->json(["message" => "Erro ao verificar filmes."], 404);
        }
    }

    /**
     * Função responsável por cadastrar um novo filme.
     *
     * @param $movie_api
     * @return mix null || Movie $movie
     */
    public function storeNewMovie($movie_api) {
        try {
            $movie = $this->movies->create([
                'poster_path' => 'https://image.tmdb.org/t/p/w500' . $movie_api->poster_path,
                'title' => $movie_api->title,
                'original_title' => $movie_api->original_title,
                'overview' => $movie_api->overview,
                'release_date' => $movie_api->release_date,
                'popularity' => $movie_api->popularity,
            ]);

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Função responsável por atualizar um filme existente.
     *
     * @param Movie $movie $movie_api
     * @return mix null || Movie $movie
     */
    public function updateMovie(Movie $movie, $movie_api) {
        try {
            $movie->update([
                'poster_path' => 'https://image.tmdb.org/t/p/w500' . $movie_api->poster_path,
                'title' => $movie_api->title,
                'original_title' => $movie_api->original_title,
                'overview' => $movie_api->overview,
                'release_date' => $movie_api->release_date,
                'popularity' => $movie_api->popularity,
            ]);

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }
}
