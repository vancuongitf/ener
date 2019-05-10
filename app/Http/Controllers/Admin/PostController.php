<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Route;
use App\Model\Post;
use App\Model\Comment;
use App\Model\GoogleUser;
use App\Model\CommentLike;
use App\Model\Tag\PostTag;
use App\Model\Tag\TagLevel1;
use App\Model\Response\StatusResponse;
use App\Http\Requests\Admin\Post\AddTagRequest;
use App\Http\Requests\Admin\Post\CreatePostRequest;

class PostController extends Controller {
    public function showCreatePostForm() {
        return view('admin.post.create');
    }

    public function createPost(CreatePostRequest $request) {
        $image = $this->saveImage($request->file('image'));
        $title = $request->get('title');
        $description = "";
        if ($request->get('description') != null) {
            $description = $request->get('description');
        }
        $html = $request->get('summernote');
        $route = $request->get('route');
        $publishNow = $request->get('publish_now');
        $post = Post::create([
            'name' => $title,
            'name_search' => $request->get('name_search'),
            'description' => $description,
            'description_search' => $request->get('description_search'),
            'image' => $image,
            'content' => $html,
            'route' =>  $route,
            'is_published' => $publishNow
        ]);
        if ($post->id > 0) {
            return redirect('admin/post/' . $post->id . '/tags');            
        } else {
            return redirect()->back()->withErrors(['message' => 'Create post fail. Please try later!']);            
        }
    }

    public function showPostInfo() {
        $post = Post::where('id', Route::current()->parameter('id'))->first();
        if ($post == null) {
            abort(404);
        }
        return view('admin.post.edit')->with('post', $post);
    }

    public function reviewPost(Request $request) {
        $post = Post::where('id', Route::current()->parameter('id'))->first();
        if ($post == null) {
            abort(404);
        }
        return view('app.post.post')->with([
            'post' => $post,
        ]);
    }

    public function getPostInfo() {
        return json_encode(Post::all()->first());
    }

    public function updatePostInfo(CreatePostRequest $request) {
        $newImage = Post::where('id', $request->input('id'))->first()->image;
        switch($request->input('image_control')) {
            case 'REMOVE': 
                $newImage = "";
                break;
            case 'CHANGE': 
                $newImage = $this->saveImage($request->file('image'));
                break;
        }
        $post = Post::where('id', $request->input('id'))
            ->update([
                'name' => $request->input('title'),
                'name_search' => $request->get('name_search'),
                'route' => $request->input('route'),
                'description' => $request->input('description'),
                'description_search' => $request->get('description_search'),
                'content' => $request->input('summernote'),
                'image' => $newImage,
                'is_published' => $request->get('publish_now')
            ]);
        if ($post > 0) {
            return redirect()->back()->withErrors(['message' => 'Update post info success!']);
        } else {
            return redirect()->back()->withErrors(['message' => 'Update post info fail!']);
        }
    }

    public function deletePost() {
        $id = Route::current()->parameter('id');
        PostTag::where('post_id', $id)->delete();
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

    public function publishPost() {
        $id = Route::current()->parameter('id');   
        $post = Post::where('id', $id)
            ->update([
                'is_published' => '1'
            ]);
        if ($post > 0) {
            return json_encode( new StatusResponse([
                'status' => 'success'
            ]));
        } else {
            return json_encode( new StatusResponse([
                'status' => 'fail'
            ]));
        }     
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

    // Delete
    public function addCommentToPost(Request $request) {
        $commentBody = json_decode($request->getContent());
        $comment = Comment::create([
            'post_id' => $commentBody->post_id,
            'user_google_id' => $commentBody->user_google_id,
            'content' => $commentBody->content
        ]);
        $comments = Comment::where('post_id', $commentBody->post_id)
            ->where('id', '>', $commentBody->max_id)
            ->orderBy('created_at', 'desc')
            ->get();
        if ($comments == null) {
            $comments = array();
        }
        foreach($comments as $comment) {
            $comment->user = GoogleUser::where('id', $comment->user_google_id)->first();
        }
        if ($comment != null) {
            $response = new StatusResponse([
                $status = 'success'
            ]);
        } else {
            $response = new StatusResponse([
                $status = 'fail'
            ]);
        }
        $response->comments = $comments;
        $response->max_id = $comments[0]->id;
        return json_encode($response);
    }

    // Delete
    public function getPostComments() {
        $postId = Route::current()->parameter('postId');
        $syncId = Route::current()->parameter('syncId');
        $userId = Route::current()->parameter('userId');
        if ($syncId < 0) {
            $comments = Comment::where('post_id', $postId)
            ->orderBy('created_at', 'desc')
            ->take(11)
            ->get();
        } else {
            $comments = Comment::where('post_id', $postId)
            ->where('id', '<', $syncId)
            ->orderBy('created_at', 'desc')
            ->take(11)
            ->get();
        }
        $response = new StatusResponse([
            'status' => 'success'
        ]);
        $cms = array();
        for ($i=0; $i < 10 && $i < count($comments); $i++) { 
            array_push($cms, $comments[$i]);
        }
        if (count($comments) > 10) {
            $response->next_page_flag = true;
        } else {
            $response->next_page_flag = false;
        }
        foreach($cms as $comment) {
            $comment->user = GoogleUser::where('id', $comment->user_google_id)->first();
            $comment->like_count = CommentLike::where('comment_id', $comment->id)->get()->count();
            if ($userId != null) {
                $comment->like_flag = CommentLike::where('comment_id', $comment->id)
                    ->where('user_google_id', $userId)
                    ->first() != null;
            } else {
                $comment->like_flag = false;
            }
        }
        if(count($cms) > 0) {
            $response->max_id = $cms[0]->id;
            $response->min_id = $cms[count($cms) - 1]->id;
            $response->comments = $cms;
        } else {
            $response->max_id = -1;
            $response->min_id = $syncId;
            $response->comments = array();
        }
        return json_encode($response);
    }

    // Delete
    public function getLikeFlag() {
        $postId = Route::current()->parameter('postId');
        $userId = Route::current()->parameter('userId');
        $ids = array();
        $commentIds = DB::table('comments')->select('id')->where('post_id', $postId)->get();
        foreach ($commentIds as $commentId) {
            array_push($ids, $commentId->id);
        }
        $rs = DB::table('comment_like')
            ->where('user_google_id', $userId)
            ->whereIn('comment_id', $ids)
            ->get();
        return json_encode($rs);
    }

    // Delete
    public function likeComment(Request $request) {
        $commentId = Route::current()->parameter('id');
        $userId = Route::current()->parameter('userId');
        $deleteLike = CommentLike::where('comment_id', $commentId)
            ->where('user_google_id', $userId)
            ->delete();
        $response = new StatusResponse([
            'status' => 'success'
        ]);
        $response->comment_id = $commentId;
        if ($deleteLike == 1) {
            $response->like_flag = false;
        } else {
            CommentLike::create([
                'comment_id' => $commentId,
                'user_google_id' => $userId
            ]);
            $like = CommentLike::where('comment_id', $commentId)
                ->where('user_google_id', $userId)
                ->first();
            $response->like_flag = $like != null;
        }
        $response->like_count = count(CommentLike::where('comment_id', $commentId)->get());
        return json_encode($response);
    }

    private function saveImage($file) {
        $filename = "";
        $uploaded = false;
        if ($file != null) {
            $destinationPath = 'file_storage/';
            $originalFile = $file->getClientOriginalName();
            $filename=md5(microtime().$originalFile).".jpg";
            $uploaded = $file->move($destinationPath, $filename);
        }
        if ($uploaded) {
            return $filename;
        } else {
            return "";
        }
    }
}
