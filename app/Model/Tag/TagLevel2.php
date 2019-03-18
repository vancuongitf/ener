<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel1;

class TagLevel2 extends Model {
    protected $table = 'tag_level_2';

    protected $fillable = [
        'name', 'route', 'tag_level_1_id'
    ];

    function childs() {
        return TagLevel3::where('tag_level_2_id', $this->id)->get();
    }

    function parent() {
        return TagLevel1::where('id', $this->tag_level_1_id)->first();
    }
}
