<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OMDBController extends Controller
{
    /**
     * Função responsável por consumir a OMDB API, requisitando
     * os filmes de acordo com a opção enviada e os retornando.
     *
     * @param json $result
     * @return json(array, code)
     */
    function matchingMovies($result){
        $api_key = 'fd6715e';
        $result2 = [];

        foreach($result->results as $movie) {
            $title = $movie->original_title;
            $url = 'http://www.omdbapi.co/?apikey='.$api_key.'&t='.$title;

            // Inicialização de requisição
            $curl = curl_init();
    
            // Configuração de opções
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               'Content-Type: application/json',
            ));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
            // Execução de requisição
            $aux = curl_exec($curl);

            // Término da requisição
            curl_close($curl);

            if(!$aux){
                return response()->json(['error' => 'Falha na conexão com a API'], 404);
            }

            $aux = json_decode($aux);

            $result2 = array_merge($result2, array($aux));
        }
        dd($result2);
        // Execução de requisição
        $result2 = json_encode($result2);

        // Retorno de resultado
        return response()->json([$result, $result2], 200);
     }
}
