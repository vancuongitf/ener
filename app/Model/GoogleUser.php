<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoogleUser extends Model {
    protected $table = 'user_google';
    protected $fillable = [
        'id', 'email', 'name', 'image'
    ];
}
