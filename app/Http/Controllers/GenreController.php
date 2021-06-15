<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Genre;
use App\Http\Controllers\TMDBController;

class GenreController extends Controller
{
    /**
     * Função Construtora.
     */
    public function __construct(Genre $genres, TMDBController $tmdb)
    {
        $this->genres = $genres;
        $this->tmdb = $tmdb;
    }

    /**
     * Função responsável por comparar todos gêneros presentes
     * no banco de dados com as vindas da API.
     *
     * @return json(array, code)
     */
    public function verifyGenresFromTMDB() {
        try {
            // recupera lista de gêneros vindos da requisição
            $genres_api = $this->tmdb->getAllGenres();
            foreach($genres_api as $genre_api) {
                // procura no banco pelo gênero vindo da requisição
                $genre = $this->genres->where('title', $genre_api->name)->first();

                if(!$genre) { // caso não encontre, será cadastrado novo gênero
                    $genre = $this->storeNewGenre($genre_api);
                }
                else { // caso encontre, o gênero será atualizado
                    $genre = $this->updateGenre($genre, $genre_api);
                }

                // caso uma das ações de cadastrar/atualizar tenha dado algum erro, será retornado uma mensagem
                if(!$genre) {
                    return response()->json(["message" => "Um erro ocorreu ao cadastrar/atualizar um gênero."], 404);
                }
            }

            // retorno com mensagem de êxito
            return response()->json(["message" => "Gêneros de Filmes verificados com sucesso."], 200);
        } catch(\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 404);
        }
    }

    /**
     * Função responsável por cadastrar um novo gênero.
     *
     * @param $genre_api
     * @return mix null || Movie $movie
     */
    public function storeNewGenre($genre_api) {
        try {
            $genre = $this->genres->create([
                'id_tmdb' => $genre_api->id,
                'title' => $genre_api->name,
            ]);

            return $genre;
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Função responsável por atualizar um gênero existente.
     *
     * @param Genre $genre $genre_api
     * @return mix null || Movie $movie
     */
    public function updateGenre(Genre $genre, $genre_api) {
        try {
            $genre->update([
                'id_tmdb' => $genre_api->id,
                'title' => $genre_api->name,
            ]);

            return $genre;
        } catch(\Exception $e) {
            return null;
        }
    }
}
