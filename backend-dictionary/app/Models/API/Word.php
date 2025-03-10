<?php

namespace App\Models\API;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $table = "words";

    protected $fillable = [
        'text'
    ];
}
