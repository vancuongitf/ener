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
@section('include-script')
    <script src="{{url('js/post-view.js')}}"></script>
@endsection
@section('page-title')
    {{ $post->name }}
@endsection
@section('meta-data')
    <meta property="og:image" content="{{url('file/ener.png')}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://ener.vn"/>
    <meta property="og:title" content="Nơi Giúp Bạn Hoàn Thiện Bản Thân Mình"/>
    <meta property="og:description" content="skajdh skjdha sdkjasbd askdbas kdas dkjas dkas dkjas dkjas dkjas dj asjkd askjd askjd jkas dkjas dkjasd kasd kajs"/> 
@endsection
@section('left-zone')
    <div class="col-lg-2 ads-side-zone" style="box-sizing:border-box; padding: 0px; margin: 0px; padding: 10px;">
    </div>
@endsection
@section('content')
<div class="post-content">
    <div class="row" style="margin: 0px; padding: 0px;">
        <div class="col-lg-8" style="padding: 0px 30px; box-sizing:border-box !important;">
            <h1><b>{{$post->name}}</b></h1>
            <p class="secondary-text">{{date("d-m-Y", strtotime($post->created_at))}} | {{$post->view_count}} Lượt xem</p>
            <b>{{$post->description}}</b> 
            {!!$post->content!!}
        </div>
        <div class="col-lg-4" style="padding: 0px 30px; margin: 0px !important;">
                @if (count($relativePosts)>0)
                <h3 style="border-bottom: 2px solid red;">Bài viết cùng chủ đề:</h3>
                @foreach ($relativePosts as $relativePost)
                    <div style="width: 100%; margin-bottom:20px;">
                        @if ($relativePost->image)
                            <div style="width: 160px; float: left;">
                                <div class="image-wrapper">
                                    <img src="{{ url('file_storage/' . $relativePost->image) }}" alt="{{$relativePost->name}}">
                                </div>
                            </div>
                        @endif
                        <a class="main-text-hover remove-text-decoration ellipse" href="{{ url('post/' . $relativePost->route) }}"><b>{{ $relativePost->name }}</b></a>              
                        <p class="secondary-text" style="margin: 0px">{{date("d-m-Y", strtotime($relativePost->created_at))}}</p>
                        <div class="clear"></div>                    
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection
