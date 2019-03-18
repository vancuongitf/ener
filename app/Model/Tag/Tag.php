<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model {
    //
    protected $fillable = [
        'name', 'childs'
    ];
}
