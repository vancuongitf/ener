<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel2;

class TagLevel3 extends Model {
    protected $table = 'tag_level_3';

    protected $fillable = [
        'name', 'route', 'tag_level_2_id'
    ];

    function parent() {
        return TagLevel2::where('id', $this->tag_level_2_id)->first();
    }
}
