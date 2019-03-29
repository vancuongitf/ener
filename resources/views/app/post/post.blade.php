<?php
    use App\Http\Controllers\Common\PostController;
    $relativePosts = PostController::getRelativePosts($post->id);
?>
@extends('layouts.app') 
<style type="text/css">
    .post-content {
        margin: 15px 0px;
    }

    .post-content img {
        max-width: 90% !important; 
        display: block !important; 
        margin-left: auto !important; 
        margin-right: auto !important;
    }
</style>
@section('content')
<div class="post-content">
    <div class="row">
        <div class="col-lg-8">
            <h1><b>{{$post->name}}</b></h1>
            <p class="secondary-text">{{$post->created_at}}</p>
            <b>{{$post->description}}</b> 
            {!!$post->content!!}
        </div>
        <div class="col-lg-4">
                @if (count($relativePosts)>0)
                <h3 style="border-bottom: 2px solid red;">Bài viết cùng chủ đề:</h3>
                @foreach ($relativePosts as $relativePost)
                    <div style="width: 100%; margin-bottom:20px;">
                        <img style="width: 160px; height: 90px; float: left; margin-right:10px;" src="{{ url('file_storage/' . $relativePost->image) }}" alt="" srcset="">
                        <a class="main-text-hover" href=""><h5>&clubs; {{$relativePost->name}}</h5></a>              
                        <div style="clear:left;"></div>                    
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
