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
use Illuminate\Database\Eloquent\Model;

class PostController extends Controller {
    
    public static function getRelativePosts($postId) {
        $posts = PostController::getRelativePostLevel3($postId);
        return $posts;
    }

    public function getHome() {
        $posts = Post::where('is_published', 1)->get();
        $mac = MacAddress::getCurrentMacAddress('eth0');
        return view('app.home.home')->with('posts', $posts)->with('mac', $mac);
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
        return view('app.category.category-posts')->with([
            'tag1' => $tag1,
            'tag2' => $tag2,
            'tag3' => $tag3,
            'categoryPosts' => $categoryPosts
        ]);
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
}

class CategoryPostResponse extends Model{
    protected $fillable = [
        'posts', 'next_page_flag', 'page'
    ];
}

class MacAddress
{

    /**
     * Regular expression for matching and validating a MAC address
     * @var string
     */
    private static $valid_mac = "([0-9A-F]{2}[:-]){5}([0-9A-F]{2})";

    /**
     * An array of valid MAC address characters
     * @var array
     */
    private static $mac_address_vals = array(
        "0", "1", "2", "3", "4", "5", "6", "7",
        "8", "9", "A", "B", "C", "D", "E", "F"
     );

    /**
     * Change the MAC address of the network interface specified
     * @param string $interface Name of the interface e.g. eth0
     * @param string $mac The new MAC address to be set to the interface
     * @return bool Returns true on success else returns false
     */
    public static function setFakeMacAddress($interface, $mac = null)
    {

        // if a valid mac address was not passed then generate one
        if (!self::validateMacAddress($mac)) {
            $mac = self::generateMacAddress();
        }

        // bring the interface down, set the new mac, bring it back up
        self::runCommand("ifconfig {$interface} down");
        self::runCommand("ifconfig {$interface} hw ether {$mac}");
        self::runCommand("ifconfig {$interface} up");

        // TODO: figure out if there is a better method of doing this
        // run DHCP client to grab a new IP address
        self::runCommand("dhclient {$interface}");

        // run a test to see if the operation was a success
        if (self::getCurrentMacAddress($interface) == $mac) {
            return true;
        }

        // by default just return false
        return false;
    }

    /**
     * @return string generated MAC address
     */
    public static function generateMacAddress()
    {
        $vals = self::$mac_address_vals;
        if (count($vals) >= 1) {
            $mac = array("00"); // set first two digits manually
            while (count($mac) < 6) {
                shuffle($vals);
                $mac[] = $vals[0] . $vals[1];
            }
            $mac = implode(":", $mac);
        }
        return $mac;
    }

    /**
     * Make sure the provided MAC address is in the correct format
     * @param string $mac
     * @return bool true if valid; otherwise false
     */
    public static function validateMacAddress($mac)
    {
        return (bool) preg_match("/^" . self::$valid_mac . "$/i", $mac);
    }

    /**
     * Run the specified command and return it's output
     * @param string $command
     * @return string Output from command that was ran
     */
    protected static function runCommand($command)
    {
        return shell_exec($command);
    }

    /**
     * Get the system's current MAC address
     * @param string $interface The name of the interface e.g. eth0
     * @return string|bool Systems current MAC address; otherwise false on error
     */
    public static function getCurrentMacAddress($interface)
    {
        $ifconfig = self::runCommand("ifconfig {$interface}");
        preg_match("/" . self::$valid_mac . "/i", $ifconfig, $ifconfig);
        if (isset($ifconfig[0])) {
            return trim(strtoupper($ifconfig[0]));
        }
        return false;
    }
}
