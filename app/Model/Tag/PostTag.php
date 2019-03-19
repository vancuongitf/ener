<?php

namespace App\Model\Tag;

use Illuminate\Database\Eloquent\Model;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;

class PostTag extends Model {
    protected $table = 'post_tag';

    protected $fillable = [
        'post_id', 'tag_level_1_id', 'tag_level_2_id', 'tag_level_3_id' 
    ];

    public function getTagLevel1() {
        return TagLevel1::where('id', $this->tag_level_1_id)->first();
    }

    public function getTagLevel2() {
        return TagLevel2::where('id', $this->tag_level_2_id)->first();
    }

    public function getTagLevel3() {
        return TagLevel3::where('id', $this->tag_level_3_id)->first();
    }

    public function getRoute() {
        $level=1;
        $id = $this->tag_level_1_id;
        if ($this->tag_level_2_id != null) {
            $level = 2;
            $id = $this->tag_level_2_id;
            if ($this->tag_level_3_id != null) {
                $level = 3;
                $id = $this->tag_level_3_id;
            }
        }
        return 'admin/tag/' . $level . '/' . $id;
    }

    public function getName() {
        $name = $this->getTagLevel1()->name;
        $tagLevel2 = $this->getTagLevel2();
        $tagLevel3 = $this->getTagLevel3();
        if ($tagLevel2 != null) {
            $name = $name . ' > ' . $tagLevel2->name;
            if ($tagLevel3 != null) {
                $name = $name . ' > ' . $tagLevel3->name;
            }
        }
        return $name;
    }
}
