<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;
use App\Http\Requests\Admin\Tag\CreateTagRequest;
use Route;

class TagController extends Controller {
    
    public function getTagHome() {
        return view('admin.tag.home')->with(['tags' => TagLevel1::all()]);
    }

    public function getCreateTag() {
        return view('admin.tag.create')->with(['taglv1s' => TagLevel1::all()]);
    }

    public function createTag(CreateTagRequest $request) {
        switch($request->input('level')) {
            case '1':
                TagLevel1::create([
                    'name' => $request->input('name'),
                    'route' => $request->input('route')
                ]);
                break;
            case '2':
                TagLevel2::create([
                    'name' => $request->input('name'),
                    'route' => $request->input('route'),
                    'tag_level_1_id' => $request->input('level_1_parent')
                ]);
                break;
            case '3':
                TagLevel3::create([
                    'name' => $request->input('name'),
                    'route' => $request->input('route'),
                    'tag_level_2_id' => $request->input('level_2_parent')
                ]);    
                break;
        }
        return redirect()->back();
    }

    public function getTagChilds() {
        $level = Route::current()->parameter('level');
        $id = Route::current()->parameter('id');
        $tag = null;
        switch($level) {
            case '1':
                $tag = TagLevel1::Where('id', $id)->first();
                break;
            
            case '2':
                $tag = TagLevel2::Where('id', $id)->first();
                break;
        }
        if ($tag!=null) {
            return json_encode($tag->childs());
        }
        return json_encode(array());
    }
}
