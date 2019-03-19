<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Route;
use App\Model\Post;
use App\Model\Tag\PostTag;
use App\Model\Tag\TagLevel1;
use App\Model\Response\StatusResponse;
use App\Http\Requests\Admin\Post\AddTagRequest;

class PostController extends Controller {
    public function showCreatePostForm() {
        return view('admin.post.create');
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

    public function deletePost() {
        $id = Route::current()->parameter('id');
        $deleteCount = Post::where('id', $id)->delete();
        if ($deleteCount > 0) {
            return json_encode(new StatusResponse([
                'status' => 'success'
            ]));
        } else {
            return json_encode(new StatusResponse([
                'status' => 'fail'
            ]));
        }
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
}
