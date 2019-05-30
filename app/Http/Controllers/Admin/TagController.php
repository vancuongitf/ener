<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Tag\Tag;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\TagLevel3;
use App\Model\Tag\PostTag;
use App\Model\Response\StatusResponse;
use App\Http\Requests\Admin\Tag\CreateTagRequest;
use App\Http\Requests\Admin\Tag\UpdateTagRequest;
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

    public function showTagInfo() {
        $level = Route::current()->parameter('level');
        $id = Route::current()->parameter('id');
        $tagWrapper = new Tag([
            'id' => $id,
            'level' => $level
        ]);
        $tag = $tagWrapper->getInfo();
        if ($tag == null) {
            abort(404);
        } 
        return view('admin.tag.info')->with('tag', $tag);
    }

    public function updateTagInfo(UpdateTagRequest $request) {
        $updateCount = 0;
        switch($request->input('level')) {
            case 1:
                $updateCount = TagLevel1::where('id', $request->input('id'))
                    ->update([
                        'name' => $request->input('name'),
                        'route' => $request->input('route')
                    ]);
                break;
            case 2:
                $updateCount = TagLevel2::where('id', $request->input('id'))
                    ->update([
                        'name' => $request->input('name'),
                        'route' => $request->input('route')
                    ]);
                break;
            case 3:
                $updateCount = TagLevel3::where('id', $request->input('id'))
                ->update([
                    'name' => $request->input('name'),
                    'route' => $request->input('route')
                ]);
                break;
        }
        if ($updateCount > 0) {
            return redirect()->back()->withErrors(['message' => 'Update tag info success!']);
        } else {
            return redirect()->back()->withErrors(['message' => 'Update tag info fail!']);
        }
    }

    public function deleteTag() {
        $level = (int)Route::current()->parameter('level');
        $id = (int)Route::current()->parameter('id');
        if ($this->removeTag($level, $id)) {
            return json_encode(new StatusResponse([
                    'status' => 'success'
                ]
            ));
        } else {
            return json_encode(new StatusResponse([
                    'status' => 'fail'
                ]
            ));
        }
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
            return json_encode($tag->getChilds());
        }
        return json_encode(array());
    }

    private function removeTag($level, $id) {
        switch($level) {
            case 1:
                $childs = TagLevel2::where('tag_level_1_id', $id)->get();
                foreach ($childs as $tag2) {
                    $this->removeTag(2, $tag2->id);
                }
                PostTag::where('tag_level_1_id', $id)->delete();
                return TagLevel1::where('id', $id)->delete();
                break;
            case 2:
                $childs = TagLevel3::where('tag_level_2_id', $id)->get();
                foreach ($childs as $tag3) {
                    $this->removeTag(3, $tag3->id);
                }
                PostTag::where('tag_level_2_id', $id)->delete();
                return TagLevel2::where('id', $id)->delete();
                break;
            
            case 3:
                PostTag::where('tag_level_3_id', $id)->delete();
                return TagLevel3::where('id', $id)->delete();                
                break;
        }
    }
}
