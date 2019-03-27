@extends('layouts.admin')
@section('include_script')
    <script src="{{ url('js/post.js') }}"></script>
@endsection
@section('script')
    <script>
        $(document).ready( function() {
            if ($('#getPostInfo').val() == "GET") {
                $('#title').val("{{$post->name}}");
                $('#route').val("{{$post->route}}");
                $('#description').val("{{$post->description}}");
                var str = '<?php echo $post->content;?>';
                $('#summernote').summernote('code', str);
                if ('{{$post->image}}'.length > 0) {
                    postImage = "{{url('file_storage/' . $post->image)}}";
                    $('#img_create_post_image').attr('src',"{{url('file_storage/' . $post->image)}}");
                    $('#btn_remove_image').show();
                }
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
                    <form id="post_form" action="" method="POST" enctype="multipart/form-data">
                        @csrf 
                        @if($errors->first('message'))
                            <p class="red-text" style="font-size: 1rem;">{{$errors->first('message')}}</p>
                        @endif
                        
                        <input id="image_control" type="hidden" name="image_control" value="NOTHING">
                        <input id="postId" name="id" type="hidden" name="post_id" value="{{$post->id}}">
                        <input id="name_search" type="hidden" name="name_search">
                        <input id="description_search" type="hidden" name="description_search">
                        <input id="publish_now" type="hidden" name="publish_now" value="0">
                        <input type="hidden" name="getPostInfo" id="getPostInfo" value="GET">
                        <div class="form-group">
                            <input id="title" name="title" type="text" placeholder="Input post's title" class="form-control" value="{{old('title')}}">                                    
                            @if ($errors->first('title'))
                                <p class="red-text" style="font-size: 1rem;">{{$errors->first('title')}}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <input id="route" name="route" type="text" placeholder="Input post's route" class="form-control" value="{{old('route')}}">                                    
                            @if ($errors->first('route'))
                                <p class="red-text" style="font-size: 1rem;">{{$errors->first('route')}}</p>
                            @endif 
                            @if ($errors->first('route-regex'))
                                <p class="red-text" style="font-size: 1rem;">Post's route is incorrect format!</p>
                            @endif
                            @if ($errors->first('route-exist'))
                                <p class="red-text" style="font-size: 1rem;">Post's route is already exists!</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <input class="form-control" id="edit_post_image" type="file" name="image" accept="image/*" onchange="showImageTo(this, '#img_create_post_image');"/>
                        </div>
                        <div class="width: 100%">
                            <div id="btn_remove_image" class="btn btn-primary" style="display:none;" onclick="removeImage()">Remove Image</div>
                            <div id="btn_dont_change_image" class="btn btn-primary" onclick="dontChangeImage()">Don't Change Image</div>                            
                        </div>
                        <img id="img_create_post_image" style="max-width:100%; margin-top: 20px;" src="" alt="">
                        <div class="form-group">
                            <textarea class="form-control" style="margin-top:20px;" name="description" id="description" cols="30" rows="5" placeholder="Input post's description">{{old('description')}}</textarea>
                        </div>
                        <div class="form-group">
                        <textarea name="summernote" id="summernote" cols="30" rows="10" class="form-control" placeholder="Input post's content">{{old('summernote')}}</textarea>                                    
                        @if ($errors->first('summernote'))
                            <p class="red-text" style="font-size: 1rem;">{{$errors->first('summernote')}}</p>
                        @endif
                        </div>
                        <div class="form-group">
                            <div id="viewHTML" class="btn btn-primary">View content HTML</div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary form-control" type="button" onclick="submitClick()">Update</button>
                        </div>
                    </form>

                    <div id="view">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
