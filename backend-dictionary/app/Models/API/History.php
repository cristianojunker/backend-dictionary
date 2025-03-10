<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = "history";

    protected $fillable = [
        'user_id',
        'word_id',
    ];

    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}
