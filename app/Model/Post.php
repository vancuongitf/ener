<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

    protected $table = 'posts';

    protected $fillable = [
        'name', 'image', 'content', 'description', 'route', 'posted_at', 'is_published'
    ];

    public function isHot() {
        if ($this->is_hot == 1) {
            return 'checked';
        } else {
            return '';
        }
    }

    public function isHightLight() {
        if ($this->is_hight_light == 1) {
            return 'checked';
        } else {
            return '';
        }
    }

    public function sluggable() {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getPostedTime() {
        return date("Y-m-d H:m", $this->posted_at);
    }
}
