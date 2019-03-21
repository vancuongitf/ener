<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;

class Tag extends Model {
    protected $fillable = [
        'id', 'level'
    ];

    public function getInfo() {
        switch($this->level) {
            case 1:
                $this->tag = TagLevel1::where('id', $this->id)->first();
                break;
            case 2:
                $this->tag = TagLevel2::where('id', $this->id)->first();
                break;
            case 3:
                $this->tag = TagLevel3::where('id', $this->id)->first();
                break;
        }
        return $this->tag;
    }

    public function parent() {
        if ($this->tag == null) {
            $this->getInfo();
        }
        if ($this->tag == null) {
            return null;
        } else {
            return $this->tag->parent();
        }
    }

    public function childs() {
        if ($this->tag == null) {
            $this->getInfo();
        }
        if ($this->tag == null) {
            return null;
        } else {
            return $this->tag->childs();
        }
    }
}
