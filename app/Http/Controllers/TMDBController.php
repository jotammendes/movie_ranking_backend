<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TMDBController extends Controller
{
    /**
     * Função responsável por consumir a The Movie DB API, requisitando
     * os gêneros de filmes existentes.
     *
     * @return json(array, code) | array
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
                return response()->json(['error' => 'Erro ao gerar lista de gêneros.'], 404);
            }

            // Término da requisição
            curl_close($curl);

            // decodifica requisição de string para json
            $genres = json_decode($genres);

            // Retorna a resposta da requisição
            return $genres->genres;
        } catch(\Exception $e) {
            return response()->json(['error' => "Erro ao gerar lista de gêneros."], 404);
        }
     }

    /**
     * Função responsável por consumir a The Movie DB API, requisitando
     * os filmes de acordo com a opção enviada e os retornando.
     *
     * @return json(array, code)
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
                return response()->json(['error' => 'Erro ao gerar lista de filmes.'], 404);
            }

            // Término da requisição
            curl_close($curl);

            // decodifica requisição de string para json
            $movies = json_decode($movies);

            // Retorna a resposta da requisição
            return response()->json($movies, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => "Erro ao gerar lista de filmes."], 404);
        }
     }

     /**
     * Função responsável por contruir uma lista com os nomes dos filmes.
     *
     * @param response()->json() $movies
     * @return json(array, code)
     */
    public function getTheTitleMovies($movies){
        try {
            $titles = [ "titles" => [] ];

            foreach($movies->results as $movie) {
                $title = $movie->original_title;
                $titles['titles'] = array_merge($titles['titles'], array($title));
            }
    
            // Retorna a lista de títulos
            return response()->json($titles, 200);
        } catch(\Exception $e) {
            return response()->json(['error' => "Ocorreu um erro ao gerar lista de títulos de filmes."], 404);
        }
     }
}
