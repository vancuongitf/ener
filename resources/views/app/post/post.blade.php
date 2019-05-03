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
    <script src="https://apis.google.com/js/platform.js" async defer></script>
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
<script>
    postId = {{$post->id}};
    function onSignIn(googleUser) {
        var profile = googleUser.getBasicProfile();
        $('#user-avatar').attr('src', profile.getImageUrl());
        $.ajax({
            type: 'POST',
            url: '/api/admin/google/user',
            data: JSON.stringify(profile),
            success: function(msg) {
                user = JSON.parse(msg);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                user = null;
            }
        });
    }

    function commentSubmit() {
        if (user != null) {
            addPostToComment($('#comment').val());
        } else {
            $('.abcRioButtonContentWrapper').first().click();
        }
    }
    minId = {{$post->comment->min_id}};
    maxId = {{$post->comment->max_id}};
    nextPageFlag = <?php if($post->comment->next_page_flag) echo 'true'; else echo 'false';?>;
</script>
<div class="post-content">
    <div class="row" style="margin: 0px; padding: 0px;">
        <div class="col-lg-8" style="padding: 0px 30px; box-sizing:border-box !important;">
            <h1><b>{{$post->name}}</b></h1>
            <p class="secondary-text">{{date("d-m-Y", strtotime($post->created_at))}} | {{$post->view_count}} Lượt xem</p>
            <b>{{$post->description}}</b> 
            {!!$post->content!!}
        <div class="row">
            <div class="dashed-bg" style="width: 100%; height: 10px;"></div>
            <div class="clear-css">
            </div>
            <div class="row" style="width: 100%; margin: 0px; padding: 0px; margin-top: 20px; margin-bottom: 20px;">
                <div class="d-flex justify-content-between" style="width: 100%;">
                    <h4>Bình luận</h4>
                    <div class="g-signin2" data-onsuccess="onSignIn"></div>
                </div>
                <div class="clear-css"></div>
                <div class="d-flex justify-content-start" style="width: 100%; padding: 10px;">
                    <img id="user-avatar" src="{{ url('file/default-avatar.jpg') }}" style="width: 50px; height: 50px; margin-right: 20px !important;">
                    <textarea name="" id="comment" cols="500" rows="3" maxlength="1000"></textarea>
                </div>
                <button id="btn-comment" class="btn btn-primary" style="margin-left: 80px;" onclick="commentSubmit()">Đăng bình luận</button>
                <button id="btn-commenting" class="btn btn-primary" type="button" style="margin-left: 80px;" disabled>
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Đang đăng bình luận...                        
                </button>
                <div id="comment-zone" class="row" style="width: 100%; margin: 0px; padding: 0px; margin-top: 20px;">
                    @foreach ($post->comment->comments as $comment)
                        <div class="d-flex" style="width: 100%; padding: 10px;">
                            <img id="user-avatar" src="{{ $comment->user->image }}" style="width: 50px; height: 50px; margin: 0px !important; margin-right: 20px !important;">
                            <div style="width: 100%; border-bottom: 1px solid #EEEEEE;">
                                <b>{{$comment->user->name}}</b>
                                <p class="secondary-text" style="margin: 5px 0px 0px 0px;">{{$comment->created_at}}</p>
                                <p class="main-text" style="margin: 5px 0px 0px 0px;">{{$comment->content}}</p>
                            </div>                            
                        </div>
                    @endforeach
                </div>
                <div class="d-flex" style="width: 100%; padding: 10px;">
                    @if ($post->comment->next_page_flag)
                        <button id="view-more-commtent" class="btn btn-primary" onclick="viewMoreComments()">Xem thêm</button>                            
                    @endif
                    <button id="loading-view" class="btn btn-primary" type="button" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...                        
                    </button>
                </div>
            </div>
            <div class="dashed-bg" style="width: 100%; height: 10px; margin-bottom: 20px;"></div>
        </div>
        </div>
        <div class="col-lg-4" style="padding: 0px 30px;">
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
