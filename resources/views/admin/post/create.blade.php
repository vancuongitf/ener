@extends('layouts.admin') 
@section('content')
<div class="row" style="font-size: 1.5rem; padding: 0px; margin: 0px;">
        <div class="col-12">
            <div class="card card-default" style="margin: 20px;">
                <div class="card-header">Create New Post</div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf 
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
                        </div>
                        <div class="form-group">
                            <input class="form-control" id="create_post_image" type="file" name="image" accept="image/*" onchange="readURL(this);"/>
                            <img id="create_post_image" style="max-width:100%; margin-top: 20px;" src="" alt="">
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="description" id="description" cols="30" rows="5" placeholder="Input post's description">{{old('description')}}</textarea>
                        </div>
                        <div class="form-group">
                        <textarea name="summernote" id="summernote" cols="30" rows="10" class="form-control" placeholder="Input post's content">{{old('summernote')}}</textarea>                                    
                        @if ($errors->first('summernote'))
                            <p class="red-text" style="font-size: 1rem;">Post's content is required!</p>
                        @endif
                        </div>
                        <div class="form-group">
                            <button id="viewHTML" class="btn btn-primary">View content HTML</button>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary form-control" type="submit" value="Create">
                        </div>
                    </form>

                    <div id="view">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
