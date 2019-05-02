<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;

class LoginController extends Controller {
    public function redirectToProvider() {
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback() {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return "xxx";
        }
        return json_encode($user);
    }
}
