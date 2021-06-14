<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Genre;

class GenreController extends Controller
{
    /**
     * Função Construtora.
     */
    public function __construct(TMDBController $tmdb)
    {
        $this->tmdb = $tmdb;
    }

    /**
     * Função responsável por comparar todos gêneros presentes
     * no banco de dados com as vindas da API.
     *
     * @return json(array, code)
     */
    public function verifyAllGenres() {
        try {
            $genres_api = $this->tmdb->getAllGenres();

            foreach($genres_api as $genre_api) {
                $genre = Genre::where('title', $genre_api->name)->where('id_tmdb', $genre_api->id)->first();

                if(!$genre) {
                    $resp = $this->storeNewGenre($genre_api);
                }
                elseif($genre->id_imdb != $genre_api->id) {
                    $resp = $this->updateGenre($genre, $genre_api);
                }

                if(!$resp) {
                    return response()->json(["message" => "Um erro ocorreu ao cadastrar/atualizar um gênero."], 404);
                }
            }
            $genres = Genre::all();
            dd($genres);

            return response()->json(["message" => "Gêneros de Filmes verificados com sucesso."], 200);
        } catch(\Exception $e) {
            return response()->json(["message" => "Erro ao verificar gêneros de filmes."], 404);
        }
    }

    /**
     * Função responsável por cadastrar um novo gênero.
     *
     * @return boolean
     */
    public function storeNewGenre($genre_api) {
        try {
            Genre::create([
                'id_tmdb' => $genre_api->id,
                'title' => $genre_api->name,
            ]);

            return true;
        } catch(\Exception $e) {
            return false;
        }
    }

    /**
     * Função responsável por atualizar um gênero existente.
     *
     * @return boolean
     */
    public function updateGenre(Genre $genre, $genre_api) {
        try {
            $genre->update([
                'id_tmdb' => $genre_api->id,
                'title' => $genre_api->name,
            ]);

            return true;
        } catch(\Exception $e) {
            return false;
        }
    }
}
