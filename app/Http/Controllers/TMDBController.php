<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TMDBController extends Controller
{
    /**
     * Função responsável por consumir a The Movie DB API, requisitando
     * os filmes de acordo com a opção enviada e os retornando.
     *
     * @return json(array, code)
     */
    function getTopRatedMovies(){
        try {
            // Inicializando url com as devidas informações
            $search_type = 'top_rated';
            $api_key = 'e407cead36f3612d403631d444d850f6';
            $released_year = date('Y');
            $language = 'pt-BR';
            $url = 'https://api.themoviedb.org/3/movie/'.$search_type.'?&api_key='.$api_key.'&primary_release_year='.$released_year.'&language='.$language;

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
                return response()->json(['error' => 'Falha na conexão com a API.'], 404);
            }

            // Término da requisição
            curl_close($curl);

            // decodifica requisição de string para json
            $movies = json_decode($movies);

            $titles = $this->getTheTitleMovies($movies);

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
    function getTheTitleMovies($movies){
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
