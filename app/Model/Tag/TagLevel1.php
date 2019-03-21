<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel2;

class TagLevel1 extends Model {
    
    public $level = 1;
    protected $table = 'tag_level_1';

    protected $fillable = [
        'name', 'route'
    ];

    public function getParent() {
        return null;
    }

    function getChilds() {
        return TagLevel2::where('tag_level_1_id', $this->id)->get();
    }
}
