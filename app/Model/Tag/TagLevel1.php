<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel2;

class TagLevel1 extends Model {
    
    protected $table = 'tag_level_1';

    protected $fillable = [
        'name', 'route'
    ];

    function childs() {
        return TagLevel2::where('tag_level_1_id', $this->id)->get();
    }
}
