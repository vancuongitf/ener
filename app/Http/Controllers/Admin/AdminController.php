<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Auth;
use App\Model\Post;
use App\Model\Tag\PostTag;
use App\Model\Response\StatusResponse;
use App\Model\Tag\TagLevel1;
use Route;
use App\Http\Requests\Admin\Post\AddTagRequest;

class AdminController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    public function __constructor() {
        $this->middlleware('admins', ['except' => 'logout']);
    }

    public function showLoginForm() {
        return view('admin.login');
    }

    public function getHome() {
        $posts = Post::all();
        return view('admin.home')->with('posts', $posts);
    }

    public function showCreatePostForm() {
        return view('admin.post.create');
    }

    public function login(Request $request)
    {
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        return $this->sendFailedLoginResponse($request);
    }

    public function createPost(Request $request) {
        $file = $request->file('image');
        $destinationPath = 'file_storage/';
        $originalFile = $file->getClientOriginalName();
        $filename=md5(strtotime(date('Y-m-d-H:isa')).$originalFile).".jpg";
        $uploaded = $file->move($destinationPath, $filename);
        if ($uploaded) {
            $title = $request->get('title');
            $description = $request->get('description');
            $html = $request->get('summernote');
            $route = $request->get('route');
            date_default_timezone_set("Asia/Bangkok");
            $t=time();
            $post = Post::create([
                'name' => $title,
                'description' => $description,
                'image' => $filename,
                'content' => $html,
                'route' =>  $route,
                'posted_at' => $t,
            ]);
        } else {
            
        }
        return redirect('admin/post/' . $post->id . '/tags');
    }

    public function showPostTags() {
        $id = Route::current()->parameter('id');
        $post = Post::where('id', $id)->first();
        if ($post == null) {
            abort(404);
        } else {
            $postTags = PostTag::where('post_id', $id)->orderBy('tag_level_1_id','asc')->orderBy('tag_level_2_id','asc')->orderBy('tag_level_3_id','asc')->get();
            $tagLevel1s = TagLevel1::all();
            return view('admin.post.tag')->with('post', $post)->with('tags', $postTags)->with('tagLevel1s', $tagLevel1s);
        }
    }

    public function addPostTag(AddTagRequest $request) {
        $postId = $request->input('post_id');
        $tagLevel1Id = $request->input('tag_level_1');
        $tagLevel2Id = $request->input('tag_level_2');
        $tagLevel3Id = $request->input('tag_level_3');
        PostTag::create([
            'post_id' => $postId,
            'tag_level_1_id' => $tagLevel1Id,
            'tag_level_2_id' => $tagLevel2Id,
            'tag_level_3_id' => $tagLevel3Id
        ]);
        if ($tagLevel3Id != null) {
            PostTag::where('post_id', $postId)->where('tag_level_2_id', $tagLevel2Id)->where('tag_level_3_id', NULL)->delete();
            
        }
        if($tagLevel2Id != null) {
            PostTag::where('post_id', $postId)->where('tag_level_1_id', $tagLevel1Id)->where('tag_level_2_id', NULL)->delete();
        }
        return redirect()->back();
    }

    public function removePostTag() {
        $id = Route::current()->parameter('id');
        $postDeletedCount = PostTag::where('id', $id)->delete();
        if ($postDeletedCount > 0) {
            return json_encode(new StatusResponse([
                'status' => 'success'
            ]));        
        } else {
            return json_encode(new StatusResponse([
                'status' => 'fail'
            ]));
        }
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
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
    protected function attemptLogin(Request $request)
    {
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
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
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
    protected function authenticated(Request $request, $user)
    {
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
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
       Auth::guard('admin')->logout();
       return redirect('admin');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    function generateURL ($str) {
        $charMap = array(
            // In thường
            "a" => "á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ",
            "d" => "đ",
            "e" => "é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ",
            "i" => "í|ì|ỉ|ĩ|ị",
            "o" => "ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ",
            "u" => "ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự",
            "y" => "ý|ỳ|ỷ|ỹ|ỵ",
            // In hoa
            "A" => "Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ",
            "D" => "Đ",
            "E" => "É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ",
            "I" => "Í|Ì|Ỉ|Ĩ|Ị",
            "O" => "Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ",
            "U" => "Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự",
            "Y" => "Ý|Ỳ|Ỷ|Ỹ|Ỵ",
            "" => "\"|\:|\;|\,"
        );
    
        foreach($charMap as $replace => $search){
            $str = preg_replace("/($search)/i", $replace, $str);
        }
        return strtolower(str_replace(" ", "-", $str));
    }
}
