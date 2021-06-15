<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OMDBController extends Controller
{
     /**
     * Função responsável por consumir a OMDB API, requisitando
     * um filme pelo título.
     *
     * @param $movie_title
     * @return mix array or null
     */
    function matchingMovie($movie_title) {
        try {
            // Formatando possíveis espaços vazios da URL
            $movie_title = str_replace(" ", "+", $movie_title);

            // Inicializando url com as devidas informações
            $api_key = '?apikey='.env('API_KEY_OMDB');
            $title = '&t='.$movie_title;
            $filter = $title;
            $url = 'http://www.omdbapi.com/'.$api_key.$filter;

            // Inicialização de requisição
            $curl = curl_init();
    
            // Configuração de opções
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
            // Execução de requisição
            $movie_api = curl_exec($curl);
            
            // Término da requisição
            curl_close($curl);
            if(!$movie_api){
                return null;
            }
    
            // decodifica requisição de string para json
            $movie_api = json_decode($movie_api);

            // converte valor de votação para padrão 0.0
            $this->getVoteFormatted($movie_api);
    
            // Retorna a resposta da requisição
            return $movie_api;
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Função responsável por consumir a OMDB API, requisitando
     * um filme pelo título.
     *
     * @param $vote
     * @return mix array or null
     */
    public function getVoteFormatted($vote) {
        try {
            // salva valor de votação em variável
            $vote_formatted = $vote;
            // Transforma valor em inteiro
            $vote_formatted = floatval($vote_formatted);
            // Verifica se valor possui mais de uma casa decimal
            if($vote_formatted > 10)
                $vote_formatted = $vote_formatted/10;

            return $vote_formatted;
        } catch(\Exception $e) {
            return null;
        }
    }
}
