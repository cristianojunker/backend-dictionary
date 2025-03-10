<?php

namespace App\Util;

use Illuminate\Support\Facades\Http;

class DictionaryAPI{

    public static function entries($word){
        try{
            $response = Http::get('https://api.dictionaryapi.dev/api/v2/entries/en/'.$word);

            $data = [];
    
            switch($response->status()){
                case 200:
                    $data = response()->json($response->body(), 200);
                    break;
                default:
                $data = response()->json(['message' => 'Houve um erro ao acessar a Dictionary API. Tente novamente.'], 400);
                    break;
            }
            return $data;
        }catch(\Exception $e){
            return response()->json(['message' => 'Houve um erro ao acessar a Dictionary API. Tente novamente.'], 400);
        }
    }

    public static function words(){
        try{
            return Http::get('https://raw.githubusercontent.com/dwyl/english-words/refs/heads/master/words_dictionary.json')->body();
        }catch(\Exception $e){
            return response()->json(['message' => 'Houve um erro ao buscar a lista de palavras do dicionário. Tente novamente.'], 400);
        }
    }
}