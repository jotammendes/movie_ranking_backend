<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    /**
     * Função Construtora.
     */
    public function __construct(Movie $movies, GenreMovieController $genre_movie, VoteController $votes, tMDBController $tmdb, OMDBController $omdb)
    {
        $this->movies = $movies;
        $this->genre_movie = $genre_movie;
        $this->votes = $votes;
        $this->tmdb = $tmdb;
        $this->omdb = $omdb;
    }

    /**
     * Função responsável por comparar os filmes presentes
     * no banco de dados com as vindos da API.
     *
     * @return json(array, code)
     */
    public function verifyMoviesFromTMDB() {
        try {
            // recupera lista de filmes vindos da requisição da primeira API
            $movies_api = $this->tmdb->getTopRatedMovies();

            foreach($movies_api as $key => $movie_api) {
                // recupera filme vindo da requisição da segunda API
                $movie_api2 = $this->omdb->matchingMovie($movie_api->original_title);

                // Verifica se o filme foi encontrado nas duas requisições
                if($movie_api2->Response === "True") {
                    // procura no banco pelo filme vindo da requisição
                    $movie = $this->movies->where('title', $movie_api->title)->first();

                    if(!$movie) { // caso não encontre, será cadastrado novo filme
                        $movie = $this->storeNewMovie($movie_api);
                        
                        // relacionando filmes e gêneros
                        $movie = $this->genre_movie->storeNewGenreMovie($movie_api, $movie);
                    }
                    else { // caso encontre, o filme será atualizado
                        $movie = $this->updateMovie($movie, $movie_api);
                    }

                    // verificando as médias de votos do filme
                    $movie = $this->votes->verifyVote('The Movie Database', $movie_api->vote_average, $movie);
                    foreach($movie_api2->Ratings as $vote) {
                        $movie = $this->votes->verifyVote($vote->Source, $this->omdb->getVoteFormatted($vote->Value), $movie);
                    }

                    // salva no filme o valor médio das votações
                    $movie = $this->getVoteAverage($movie);

                    // caso uma das ações de cadastrar/atualizar tenha dado algum erro, será retornado uma mensagem
                    if(!$movie) {
                        return response()->json(["message" => "Um erro ocorreu ao cadastrar/atualizar um filme."], 404);
                    }
                }
            }
            // retorno com mensagem de êxito
            return response()->json(["message" => "Filmes verificados com sucesso."], 200);
        } catch(\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 404);
        }
    }

    /**
     * Função responsável por cadastrar um novo filme.
     *
     * @param $movie_api
     * @return mix null or Movie $movie
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
     * @return mix null or Movie $movie
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

    /**
     * Função responsável por calcular a média de votos das fontes existentes.
     *
     * @param Movie $movie
     * @return mix null or Movie $movie
     */
    public function getVoteAverage(Movie $movie) {
        try {
            // adquire a soma valores de cada fonte de votos
            $sum = $movie->votes->pluck('vote_average')->sum();
            // adquire a quantidade de cada fonte de votos
            $count = $movie->votes->count();
            // calcula a média
            $avg = $sum / $count;

            // atualiza média de votos do filme
            $movie->update([
                'vote_average' => $avg,
            ]);

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }
}
