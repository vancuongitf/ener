<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller {
    
    public function uploadImage(Request $request) {
        // $file = $request->file('image');
        // $destinationPath = 'file_storage/';
        // $originalFile = $file->getClientOriginalName();
        // $filename=md5(strtotime(date('Y-m-d-H:isa')).$originalFile).".jpg";
        $uploaded = false;
        if ($uploaded) {
            return $filename;
        } else {
            return "fail";
        }
    } 
}
