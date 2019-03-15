<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Post;
use Route;
use Illuminate\Support\Facades\DB;

class PostController extends Controller {
    
    public function getPostDetail(Request $request) {
        $route = Route::current()->parameter('route');
        $result = DB::select("select * from posts where route  = ?", [$route]);
        if (count($result) > 0) {
            $row = $result[0];
            $post = new Post([
                'id' => $row->id,
                'name' => $row->name,
                'route' => $row->route,
                'image' => $row->image,
                'description' => $row->description,
                'posted_at' => $row->posted_at,
                'created_at' => $row->created_at,
                'content' => $row->content,
                'is_hight_light' => $row->is_hight_light,
                'is_hot' => $row->is_hot,
                'view_count' => $row->view_count
            ]);
            return view('app.post.post')->with([
                'post' => $post,
            ]);
        } else {
            abort(404);
        }
    }
}
