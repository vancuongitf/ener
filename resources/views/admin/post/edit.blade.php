@extends('layouts.admin')
@section('script')
<script>
    function showImageTo(input, $target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $($target).attr('src', e.target.result);
                $('#image_control').val('CHANGE');   
                $('#img_edit_post_image').show();
                $('#btn_dont_change_image').show();
                $('#btn_remove_image').show();
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function removeImage() {
        $('#img_edit_post_image').hide();
        $('#btn_dont_change_image').show();
        $('#btn_remove_image').hide();
        $('#image_control').val('REMOVE');
        var imgChoose = $('#edit_post_image');
        imgChoose.replaceWith(imgChoose.val('').clone(true));
    }

    function dontChangeImage() {
        $('#img_edit_post_image').show();
        $('#btn_remove_image').show();
        $('#image_control').val('NOTHING');
        $('#img_edit_post_image').attr('src', "{{url('file_storage/' . $post->image)}}");
        $('#btn_dont_change_image').hide();
        var imgChoose = $('#edit_post_image');
        imgChoose.replaceWith(imgChoose.val('').clone(true));
    }

    $(document).ready(function() {
        $('#btn_dont_change_image').hide();
        if ($('#getPostInfo').val() == "GET") {
            $('#title').val("{{$post->name}}");
            $('#route').val("{{$post->route}}");
            $('#description').val("{{$post->description}}");
            var str = '<?php echo $post->content;?>';
            $('#summernote').summernote('code', str);
        }
        $("#viewHTML").click( function() {
            var markupStr = $('#summernote').summernote('code');
            $('#view').html(markupStr);
        });    
        $("#summernote").summernote({
            placeholder: 'Input post\' content',
            tabsize: 2,
            height: 500
        });
        $('#title').keyup(function() {
            $('#route').val(genUrl($('#title').val()));        
        });
        function genUrl(alias) {
            var str = alias;
            str = str.toLowerCase();
            str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
            str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
            str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
            str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
            str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
            str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
            str = str.replace(/đ/g,"d");
            str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g," ");
            str = str.replace(/ + /g," ");
            str = str.trim();
            while(str.includes("  ") > 0) {
                str = str/replace("  ", " ");
            }
            while(str.includes(" ") > 0) {
                str = str.replace(" ","-");
            }
            return str.concat(".html");
        }
    });
</script>    
@endsection 
@section('content')
<div class="row" style="font-size: 1.5rem; padding: 0px; margin: 0px;">
        <div class="col-12">
            <div class="card card-default" style="margin: 20px;">
                <div class="card-header">Edit Post Info</div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @if($errors->first('message'))
                            <p class="red-text" style="font-size: 1rem;">{{$errors->first('message')}}</p>
                        @endif
                        
                        <input id="image_control" type="hidden" name="image_control" value="nothing">
                        <input id="postId" name="id" type="hidden" name="post_id" value="{{$post->id}}"> 
                        <input type="hidden" name="getPostInfo" id="getPostInfo" value="GET">
                        <div class="form-group">
                            <input id="title" name="title" type="text" placeholder="Input post's title" class="form-control" value="{{old('title')}}">                                    
                            @if ($errors->first('title'))
                                <p class="red-text" style="font-size: 1rem;">Post's title is required!</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="route" name="route" type="text" placeholder="Input post's route" class="form-control" value="{{old('route')}}">                                    
                            @if ($errors->first('route'))
                                <p class="red-text" style="font-size: 1rem;">Post's route is required!</p>
                            @endif 
                            @if ($errors->first('route-regex'))
                                <p class="red-text" style="font-size: 1rem;">Post's route is incorrect format!</p>
                            @endif
                            @if ($errors->first('route-exist'))
                                <p class="red-text" style="font-size: 1rem;">Post's route is already exists!</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <input class="form-control" id="edit_post_image" type="file" name="image" accept="image/*" onchange="showImageTo(this, '#img_edit_post_image');"/>
                        </div>
                        <div class="width: 100%">
                            <div id="btn_remove_image" class="btn btn-primary" onclick="removeImage()">Remove Image</div>
                            <div id="btn_dont_change_image" class="btn btn-primary" onclick="dontChangeImage()">Don't Change Image</div>                            
                        </div>
                        <img id="img_edit_post_image" style="max-width:100%; margin-top: 20px;" src="{{url('file_storage/' . $post->image)}}" alt="">
                        <div class="form-group">
                            <textarea class="form-control" style="margin-top:20px;" name="description" id="description" cols="30" rows="5" placeholder="Input post's description">{{old('description')}}</textarea>
                        </div>
                        <div class="form-group">
                        <textarea name="summernote" id="summernote" cols="30" rows="10" class="form-control" placeholder="Input post's content">{{old('summernote')}}</textarea>                                    
                        @if ($errors->first('summernote'))
                            <p class="red-text" style="font-size: 1rem;">Post's content is required!</p>
                        @endif
                        </div>
                        <div class="form-group">
                            <div id="viewHTML" class="btn btn-primary">View content HTML</div>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary form-control" type="submit" value="Update">
                        </div>
                    </form>

                    <div id="view">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
