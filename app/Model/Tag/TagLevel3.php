<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel2;

class TagLevel3 extends Model {
    
    public $level = 3;
    protected $table = 'tag_level_3';

    protected $fillable = [
        'name', 'route', 'tag_level_2_id'
    ];

    function getParent() {
        return TagLevel2::where('id', $this->tag_level_2_id)->first();
    }

    function getChilds() {
        return null;
    }
}
