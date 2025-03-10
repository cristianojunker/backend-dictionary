<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\API\History;
use App\Models\API\FavoriteWord;
use App\Models\API\Word;
use App\Models\API\WordCache;
use App\Util\DictionaryAPI;
use Illuminate\Support\Facades\Auth;
use PSpell\Dictionary;

class DictionaryAPIController extends Controller
{
    public function entries($word){
        
        if(empty($word)){
            return response()->json(['message' => 'É necessário informar uma palavra.'], 400)  ;
        }

        $data = WordCache::whereHas('word', function ($query) use ($word){
            return $query->where('text', '=', $word);
        })->where('user_id', Auth::user()->id)->first();

        $history = new History();
        $history->word = $word;
        $history->user_id = Auth::user()->id;
        $history->save();

        if(!empty($data)){
            return response()->json(json_decode($data->information), 200);
        }else{
            $response = DictionaryAPI::entries($word);

            $wordSearched = Word::where('text', $word)->first();

            if(!empty($wordSearched)){
                $wordCache = new WordCache();
                $wordCache->user_id = Auth::user()->id;
                $wordCache->word_id = $wordSearched->id;
                $wordCache->information = json_encode($response->getContent());
                $wordCache->save();
            }

            

            return $response;
        }

    }

    public function listHistory(){
        $data = History::where('user_id', Auth::user()->id)->paginate(20);
        return response()->json($data, 200); 
    }

    public function listFavoriteWords(){
        $data = FavoriteWord::where('user_id', Auth::user()->id)->paginate(20);
        return response()->json($data, 200); 
    }

    public function listWords(){
        $data = Word::paginate(20);
        return response()->json($data, 200); 
    }

    public function favorite($word){
        $data = FavoriteWord::whereHas('word', function ($query) use ($word){
            return $query->where('text', '=', $word);
        })->where('user_id', Auth::user()->id)->first();
        
        if(!empty($data)){
            return response()->json(['message' => 'A palavra \''.$word.'\' já foi favoritada.'], 200);
        }

        $word_id = Word::where('text', $word)->first()->id;

        $favorite = new FavoriteWord();
        $favorite->user_id = Auth::user()->id;
        $favorite->word_id = $word_id;
        $favorite->save();

        return response()->json(['message' => 'A palavra \''.$word.'\' foi adicionada como favorita.'], 200);
    }

    public function unfavorite($word){
        FavoriteWord::whereHas('word', function ($query) use ($word){
            return $query->where('text', '=', $word);
        })->where('user_id', Auth::user()->id)->delete();

        return response()->json(['message' => 'A palavra \''.$word.'\' foi removida da lista de favoritas.'], 200);

    }

    public function loadAndSaveWords(){
        $data = array_keys(json_decode(DictionaryAPI::words(), true));
        foreach($data as $text){
            $word = new Word();
            $word->text = $text;
            $word->save();
        }
        return response()->json(['message' => 'Lista de palavras carregadas com sucesso.'], 200);
    }
}
