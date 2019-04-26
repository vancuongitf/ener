<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model {
    protected $fillable = ['post_id', 'ip'];
    protected $table = 'post_views';
}
