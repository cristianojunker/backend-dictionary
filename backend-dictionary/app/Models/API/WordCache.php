<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;

class WordCache extends Model
{
    protected $table = "words_cache";

    protected $fillable = [
        'user_id',
        'word_id', 
        'information'
    ];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
