<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    protected $table = "comments";
    protected $fillable = [
        'id', 'post_id', 'user_google_id', 'content'
    ];
}
