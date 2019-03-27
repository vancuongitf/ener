<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Auth;
use Route;
use App\Model\Post;

class AdminController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    public $pageItemCount = 30;

    public function __constructor() {
        $this->middlleware('auth:admin', ['except' => 'logout']);
    }

    public function showLoginForm() {
        return view('admin.login');
    }

    public function getHome() {
        $page = Route::current()->parameter('page');
        $haveNextPage = false;
        if ($page == null) {
            $page = 1;
        }
        if (!is_numeric($page) || $page < 1) {
            return redirect('admin');
        }
        $ignore = ($page - 1) * $this->pageItemCount;
        $posts = Post::where('id', '!=', '0')->skip($ignore)->take($this->pageItemCount + 1)->get();
        if (count($posts) > $this->pageItemCount) {
            $posts = Post::where('id', '!=', '0')->skip($ignore)->take($this->pageItemCount)->get();
            $haveNextPage = true;
        }
        return view('admin.home')->with('posts', $posts)->with('showNotPublishButton', true)->with('haveNextPage', $haveNextPage)->with('page', $page);
    }

    public function search(Request $request) {
        $page = $request->get('page');
        $query = $request->get('query');
        $haveNextPage = false;
        if ($page == null) {
            $page = 1;
        }
        if (!is_numeric($page) || $page < 1) {
            return redirect('admin/search?page=1&query=' . $query);
        }
        $ignore = ($page - 1) * $this->pageItemCount;
        $posts = Post::where('name', 'like', '%' . $query . '%')->orWhere('description', 'like', '%' . $query . '%')->get();
        return view('admin.home')->with('posts', $posts)->with('showNotPublishButton', true)->with('haveNextPage', $haveNextPage)->with('page', $page);
    }

    public function showNotPublishPost() {
        $page = Route::current()->parameter('page');
        $haveNextPage = false;
        if ($page == null) {
            $page = 1;
        }
        if (!is_numeric($page) || $page < 1) {
            return redirect('admin');
        }
        $ignore = ($page - 1) * $this->pageItemCount;
        $posts = Post::where('is_published', '==', '0')->skip($ignore)->take($this->pageItemCount + 1)->get();
        if (count($posts) > $this->pageItemCount) {
            $haveNextPage = true;
            $posts = Post::where('id', '!=', '0')->skip($ignore)->take($this->pageItemCount)->get();
        }
        return view('admin.home')->with('posts', $posts)->with('showNotPublishButton', false)->with('haveNextPage', $haveNextPage)->with('page', $page);
    }

    public function login(Request $request) {
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request) {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request) {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request) {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request) {
        $request->session()->regenerate();

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectTo);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user) {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request) {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username() {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
       Auth::guard('admin')->logout();
       return redirect('admin');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request) {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard() {
        return Auth::guard('admin');
    }
}
