<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Post;
use App\Model\Comment;
use App\Model\CommentLike;
use App\Model\CommentReply;
use App\Model\GoogleUser;
use App\Model\Tag\PostTag;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;
use App\Model\PostView;
use App\Model\Util\IntSet;
use App\Model\Response\StatusResponse;
use Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PostController extends Controller {
    
    public static function getRelativePosts($postId) {
        $posts = PostController::getRelativePostLevel3($postId);
        foreach ($posts as $post) {
            $post->view_count = PostController::getPostViewCount($post->id, $post->created_at);
        }
        return $posts;
    }

    public function getHome() {
        $posts = Post::where('is_published', 1)->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post) {
            $post->view_count = PostController::getPostViewCount($post->id, $post->created_at);
        }
        return view('app.home.home')->with('posts', $posts);
    }

    public function getPostDetail(Request $request) {
        $route = Route::current()->parameter('route');
        $post = Post::where('route', $route)->where('is_published', '1')->first();
        $post->comment = $this->getPostComments($post->id);
        $post->view_count = PostController::getPostViewCount($post->id, $post->created_at);
        if ($post != null) {
            return view('app.post.post')->with([
                'post' => $post
            ]);
        } else {
            abort(404);
        }
    }

    public function getPostByTag(Request $request) {
        $level1 = Route::current()->parameter('level1');
        $level2 = Route::current()->parameter('level2');
        $level3 = Route::current()->parameter('level3');
        $tag1 = null;
        $tag2 = null;
        $tag3 = null;
        $categoryPosts = null;
        $tag1 = TagLevel1::where('route', $level1)->first();
        if ($tag1 != null) {
            $tag2 = TagLevel2::where('route', $level2)->where('tag_level_1_id', $tag1->id)->first();
            if ($tag2 != null) {
                $tag3 = TagLevel3::where('route', $level3)->where('tag_level_2_id', $tag2->id)->first();
                if ($tag3 != null) {
                    $categoryPosts = PostController::getCategoryPostsWithPage($tag3->id, 3, 1);
                } else {
                    if ($level3 != null) {
                        abort(404);
                    } else {
                        $categoryPosts = PostController::getCategoryPostsWithPage($tag2->id, 2, 1);
                    }      
                }
            } else {
                if ($level2 != null) {
                    abort(404);
                } else {
                    $categoryPosts = PostController::getCategoryPostsWithPage($tag1->id, 1, 1);
                }
            }
        } else {
            abort(404);
        }
        foreach ($categoryPosts->posts as $post) {
            $post->view_count = PostController::getPostViewCount($post->id, $post->created_at);
        }
        return view('app.category.category-posts')->with([
            'tag1' => $tag1,
            'tag2' => $tag2,
            'tag3' => $tag3,
            'categoryPosts' => $categoryPosts
        ]);
    }

    public function addPostView() {
        $postId = Route::current()->parameter('id');
        $ip = $_SERVER['REMOTE_ADDR'];
        $postView = PostView::create([
            'post_id' => $postId,
            'ip' => $ip
        ]);
        if ($postView != null) {
            return json_encode(new StatusResponse([
                'status' => 'success'
            ]));
        } else {
            return json_encode(new StatusResponse([
                'status' => 'fail'
            ])); 
        }
    }

    public function getOldPostComments() {
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

    public function getCommentReplies() {
        $response = new StatusResponse([
            'status' => 'success'
        ]);
        $response->next_page_flag = false;
        $commentId = (int)Route::current()->parameter('id');
        $syncId = (int)Route::current()->parameter('syncId');
        if ($syncId < 1) {
            $replies = CommentReply::where('comment_id', $commentId)
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get();
        } else {
            $replies = CommentReply::where('comment_id', $commentId)
            ->where('id', '<', $syncId)
            ->orderBy('created_at', 'DESC')
            ->take(6)
            ->get();
        }
        
        if ($replies->count() > 5) {
            $response->next_page_flag = true;
        }
        $repliesRs = array();
        $response->comment_id = $commentId;
        for ($i = $replies->count() - 1; $i > -1; $i--) {
            if ($i > 5) {
                continue;
            }
            $replies[$i]->user = GoogleUser::where('id', $replies[$i]->user_google_id)->first();
            array_push($repliesRs, $replies[$i]);
        }
        $response->replies = $repliesRs;
        return json_encode($response);
    }

    public function replyComment(Request $request) {
        $response = new StatusResponse([
            'status' => 'fail'
        ]);
        $commentId = (int)Route::current()->parameter('id');
        $userId = (int)Route::current()->parameter('userId');
        $syncId = (int)Route::current()->parameter('syncId');
        $content = $request->getContent();
        $reply = CommentReply::create([
            'comment_id' => $commentId,
            'user_google_id' => $userId,
            'content' => $content
        ]);
        if ($reply != null) {
            $response->status = 'success';
        }
        $newComments = CommentReply::where('id', '>', $syncId)
            ->where('comment_id', $commentId)
            ->orderBy('created_at', 'asc')
            ->get();
        foreach($newComments as $newReply) {
            $newReply->user = GoogleUser::where('id', $newReply->user_google_id)->first();
        }
        $response->replies = $newComments;
        return json_encode($response);
    }

    private static function getCategoryPostsWithPage($id, $level, $page) {
        $columnName = '';
        switch($level) {
            case '1':
                $columnName = 'tag_level_1_id';
                break;
            case '2':
                $columnName = 'tag_level_2_id';
                break;
            case '3':
                $columnName = 'tag_level_3_id';
                break;
        }
        $nextPageFlag = false;
        $posts = DB::table('posts')
            ->join('post_tags', 'posts.id', '=', 'post_tags.post_id')
            ->select('posts.id', 'posts.name', 'posts.image', 'posts.created_at', 'posts.route', 'posts.route', 'posts.description')
            ->where($columnName, $id)
            ->where('posts.is_published', '1')
            ->orderBy('created_at', 'desc')
            ->distinct('posts.id')
            ->skip(($page - 1) * 30) 
            ->take(31)
            ->get();
        if (count($posts) > 30) {
            $nextPageFlag = true;
            $posts = DB::table('posts')
                ->join('post_tags', 'posts.id', '=', 'post_tags.post_id')
                ->select('posts.id', 'posts.name', 'posts.image', 'posts.created_at', 'posts.route', 'posts.route', 'posts.description')
                ->where($columnName, $id)
                ->where('posts.is_published', '1')
                ->orderBy('created_at', 'desc')
                ->distinct('posts.id')
                ->skip(($page - 1) * 30) 
                ->take(30)
                ->get();
        }
        return new CategoryPostResponse([
            'posts' => $posts,
            'next_page_flag' => $nextPageFlag,
            'page' => $page
        ]);
    }

    private static function getRelativePostLevel3($postId) {
        $tagQueryResult = PostTag::select('tag_level_3_id')
            ->where('post_id', $postId)
            ->where('tag_level_3_id', '!=', null)
            ->distinct('tag_level_3_id')
            ->get();
        $tags = array();
        foreach ($tagQueryResult as $tag) {
            array_push($tags, $tag->tag_level_3_id);
        }
        $postIdQueryResult = PostTag::select('post_id')
            ->where('post_id', '!=', $postId)
            ->whereIn('tag_level_3_id', $tags)
            ->distinct('post_id')
            ->get();
        $postIds = array();
        foreach ($postIdQueryResult as $postIdQR) {
            array_push($postIds, $postIdQR->post_id);
        }
        $posts = Post::select('id', 'route', 'name', 'image', 'created_at')
        ->whereIn('id', $postIds)
        ->where('is_published', '1')
        ->orderBy('created_at', 'desc')
        ->take(10)
        ->get();
        if (count($posts) < 10) {
            $posts = $posts->merge(PostController::getRelativePostLevel2($postId, $postIds));
            return $posts;
        } else {
            return $posts;
        }
    }

    private static function getRelativePostLevel2($postId, $existIds) {
        $tagQueryResult = PostTag::select('tag_level_2_id')
            ->where('post_id', $postId)
            ->where('tag_level_2_id', '!=', null)
            ->distinct('tag_level_2_id')
            ->get();
        $tags = array();
        foreach ($tagQueryResult as $tag) {
            array_push($tags, $tag->tag_level_2_id);
        }
        $postIdQueryResult = PostTag::select('post_id')
            ->where('post_id', '!=', $postId)
            ->whereIn('tag_level_2_id', $tags)
            ->whereNotIn('post_id', $existIds)
            ->distinct('post_id')
            ->get();
        $postIds = array();
        foreach ($postIdQueryResult as $postIdQR) {
            array_push($postIds, $postIdQR->post_id);
        }
        $post2s = Post::select('id', 'route', 'name', 'image', 'created_at')
        ->whereIn('id', $postIds)
        ->where('is_published', '1')
        ->orderBy('created_at', 'desc')
        ->take(10 - count($existIds))
        ->get();
        if ((count($post2s) + count($existIds)) < 10) {
            foreach ($postIds as $id) {
                array_push($existIds, $id);
            }
            return $post2s->merge(PostController::getRelativePostLevel1($postId, $existIds));
        } else {
            return $post2s;
        }
    }

    private static function getRelativePostLevel1($postId, $existIds) {
        $tagQueryResult = PostTag::select('tag_level_1_id')
            ->where('post_id', $postId)
            ->where('tag_level_1_id', '!=', null)
            ->distinct('tag_level_1_id')
            ->get();
        $tags = array();
        foreach ($tagQueryResult as $tag) {
            array_push($tags, $tag->tag_level_1_id);
        }
        $postIdQueryResult = PostTag::select('post_id')
            ->where('post_id', '!=', $postId)
            ->whereIn('tag_level_1_id', $tags)
            ->whereNotIn('post_id', $existIds)
            ->distinct('post_id')
            ->get();
        $postIds = array();
        foreach ($postIdQueryResult as $postIdQR) {
            array_push($postIds, $postIdQR->post_id);
        }
        $post2s = Post::select('id', 'route', 'name', 'image', 'created_at')
        ->whereIn('id', $postIds)
        ->where('is_published', '1')
        ->orderBy('created_at', 'desc')
        ->take(10 - count($existIds))
        ->get();
        return $post2s;
    }

    private function getCategoryPosts($id, $level) {
        $columnName = '';
        switch($level) {
            case '1':
                $columnName = 'tag_level_1_id';
                break;
            case '2':
                $columnName = 'tag_level_2_id';
                break;
            case '3':
                $columnName = 'tag_level_3_id';
                break;
        }
        return DB::table('posts')
            ->join('post_tags', 'posts.id', '=', 'post_tags.post_id')
            ->select('posts.*')
            ->where($columnName, $id)
            ->where('posts.is_published', '1')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    private static function getPostViewCount($postId, $createdAt) {
        // $defaultView = (int)((time() - $createdAt->getTimestamp()) / 300);
        $count = DB::table('post_views')->selectRaw('*, count(*)')->groupBy('post_id')->where('post_id', $postId)->count();
        // return $count + $defaultView;
        return $count;
    }

    private function getPostComments($postId) {
        $comments = Comment::where('post_id', $postId)
            ->orderBy('created_at', 'desc')
            ->take(11)
            ->get();
        $response = new StatusResponse([
            'status' => 'success'
        ]);
        $cms = array();
        for ($i=0; $i < 10 && $i < count($comments); $i++) { 
            array_push($cms, $comments[$i]);
        }
        $response->next_page_flag = false;
        foreach($cms as $comment) {
            $comment->user = GoogleUser::where('id', $comment->user_google_id)->first();
            $comment->like_count = CommentLike::where('comment_id', $comment->id)->get()->count();
        }
        if(count($comments) > 0) {
            $response->max_id = $cms[0]->id;
            $response->min_id = $cms[count($cms) - 1]->id;
            $response->comments = $cms;
        } else {
            $response->max_id = -1;
            $response->min_id = -1;
            $response->comments = array();
        }
        return $response;
    }
}

class CategoryPostResponse extends Model{
    protected $fillable = [
        'posts', 'next_page_flag', 'page'
    ];
}
