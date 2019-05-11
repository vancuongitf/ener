<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    protected $table = 'posts';

    protected $fillable = [
        'id', 'name', 'name_search', 'image', 'content', 'description', 'key_words', 'new_key_words', 'description_search', 'route', 'posted_at', 'is_published', 'is_high_light', 'is_hot', 'view_count'
    ];

    public function isHot() {
        if ($this->is_hot == 1) {
            return 'checked';
        } else {
            return '';
        }
    }

    public function isHighLight() {
        if ($this->is_hight_light == 1) {
            return 'checked';
        } else {
            return '';
        }
    }

    public function getPostedTime() {
        return date("Y-m-d H:m", $this->posted_at);
    }
}
