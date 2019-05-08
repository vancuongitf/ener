<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model {
    protected $table = 'comment_like';
    protected $fillable = [
        'comment_id', 'user_google_id'
    ];
}
