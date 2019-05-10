<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model {
    protected $table = 'comment_replies';
    protected $fillable = [
        'comment_id', 'user_google_id', 'content'
    ];
}
