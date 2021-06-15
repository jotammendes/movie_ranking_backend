<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Vote;

class VoteController extends Controller
{
    /**
     * Função Construtora.
     */
    public function __construct(Vote $votes)
    {
        $this->votes = $votes;
    }

    /**
     * Função responsável por verificar a existencia dos votos médios de um filme.
     *
     * @param $name_api $vote_average Movie $movie
     * @return mix null or Vote $vote
     */
    public function verifyVote($name_api, $vote_average, Movie $movie) {
        try {
            // procura gênero no banco
            $vote = $this->votes->where('name_api', $name_api)->where('movie_id', $movie->id)->first();

            // verifica se fonte de votos existe
            if(!$vote) { // se não, será cadastrado
                $movie = $this->storeNewVote($name_api, $vote_average, $movie);
            }
            else { // se sim, será atualizado
                $movie = $this->updateVote($name_api, $vote_average, $movie);
            }

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Função responsável por cadastrar os votos de um filme.
     *
     * @param $name_api $vote_average Movie $movie
     * @return mix null or Vote $vote
     */
    public function storeNewVote($name_api, $vote_average, Movie $movie) {
        try {
            // cadastra novo valor médio de voto de uma fonte
            $vote = $this->votes->create([
                'name_api' => $name_api,
                'vote_average' => $vote_average,
                'movie_id' => $movie->id,
            ]);

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Função responsável por atualizar os votos de um filme.
     *
     * @param $name_api $vote_average Movie $movie
     * @return mix null or Vote $vote
     */
    public function updateVote($name_api, $vote_average, Movie $movie) {
        try {
            // encontra gênero no banco
            $vote = $this->votes->where('name_api', $name_api)->where('movie_id', $movie->id)->first();

            // atualiza valor médio de voto de uma fonte
            $vote->update([
                'name_api' => $name_api,
                'vote_average' => $vote_average,
                'movie_id' => $movie->id,
            ]);

            return $movie;
        } catch(\Exception $e) {
            return null;
        }
    }
}
