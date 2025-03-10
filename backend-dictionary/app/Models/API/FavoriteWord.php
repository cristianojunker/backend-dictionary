<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;

class FavoriteWord extends Model
{
    protected $table = "favorite_word";

    protected $fillable = [
        'user_id',
        'word_id'
    ];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
