<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Post;
use App\Model\Tag\PostTag;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;
use App\Model\Util\IntSet;
use Route;
use Illuminate\Support\Facades\DB;

class PostController extends Controller {
    
    public function getHome() {
        $categories = array();
        $tag1s = TagLevel1::all();
        foreach ($tag1s as $tag1) {
            $tag1->posts = PostController::getCategoryPosts($tag1->id, 1);
            if (count($tag1->posts) > 0) {
                array_push($categories, $tag1);
            }
        }
        return view('app.home.home')->with('categories', $categories);
    }

    public function getPostDetail(Request $request) {
        $route = Route::current()->parameter('route');
        $post = Post::where('route', $route)->where('is_published', '1')->first();
        if ($post != null) {
            return view('app.post.post')->with([
                'post' => $post,
            ]);
        } else {
            abort(404);
        }
    }

    public static function getRelativePosts($postId) {
        $posts = PostController::getRelativePostLevel3($postId);
        return $posts;
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
}
