<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TMDBController extends Controller
{
    /**
     * Função responsável por consumir a The Movie DB API, requisitando
     * os gêneros de filmes existentes.
     *
     * @return mix null or array
     */
    public function getAllGenres(){
        try {
            // Inicializando url com as devidas informações
            $api_key = '&api_key=' . env('API_KEY_TMDB');
            $search_type = 'list?';
            $language = '&language=pt-BR';
            $filter = $search_type . $language;
            $url = 'https://api.themoviedb.org/3/genre/movie/' . $filter . $api_key;

            // Inicialização de requisição
            $curl = curl_init();

            // Configuração de opções
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            // Execução de requisição
            $genres = curl_exec($curl);
            if(!$genres){
                return null;
            }

            // Término da requisição
            curl_close($curl);

            // decodifica requisição de string para json
            $genres = json_decode($genres);

            // Retorna a resposta da requisição
            return $genres->genres;
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Função responsável por consumir a The Movie DB API, requisitando
     * os filmes de acordo com a opção enviada e os retornando.
     *
     * @return mix null or array
     */
    public function getTopRatedMovies(){
        try {
            // Inicializando url com as devidas informações
            $api_key = '&api_key=' . env('API_KEY_TMDB');
            $search_type = 'top_rated?';
            $released_year = '&primary_release_year='.date('Y');
            $language = '&language=pt-BR';
            $filter = $search_type . $released_year . $language;
            $url = 'https://api.themoviedb.org/3/movie/' . $filter . $api_key;

            // Inicialização de requisição
            $curl = curl_init();

            // Configuração de opções
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            // Execução de requisição
            $movies = curl_exec($curl);
            if(!$movies){
                return null;
            }

            // Término da requisição
            curl_close($curl);

            // decodifica requisição de string para json
            $movies = json_decode($movies);

            // Retorna a resposta da requisição
            return $movies->results;
        } catch(\Exception $e) {
            return null;
        }
    }
}
