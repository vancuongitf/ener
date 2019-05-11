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

    .post-content span {
        line-height: 1.5 !important;
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
    <meta name="description" content="{{$post->description}}" />
    <meta name="keywords" content="{{$post->key_words}}" />
    <meta name="news_keywords" content="{{$post->new_keywords}}" />
    <link rel="canonical" href="{{url('/post/' . $post->route)}}" />
    <meta name="robots" content="index,follow,noodp" />
    <meta name="robots" content="noarchive">
    <meta property="og:site_name" content="Ener VN - Phát triển bản thân" />
    <meta property="og:type" content="article" />
    <meta property="og:locale" content="vi_VN" />
    <meta property="fb:pages" content="1735750099904628" />
	<meta property="og:title" itemprop="name" content="{{$post->name}}" />    
	<meta property="og:url" itemprop="url" content="{{url('/post/' . $post->route)}}" />
    <meta property="og:description" content="{{$post->description}}" />
    <meta content="{{url('/file_storage/' . $post->image)}}" property="og:image" itemprop="thumbnailUrl" />
    <meta name="pubdate" itemprop="datePublished" content="{{$post->created_at}}"/>
    <meta itemprop="dateModified" content="{{$post->updated_at}}" />
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
                getLikeFlag();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                user = null;
            }
        });
    }

    function commentSubmit() {
        if (user != null) {
            addCommentToPost($('#comment').val());
        } else {
            loginConfirm();
        }
    }

    function getLikeFlag() {
        $.getJSON('/api/post/'.concat(postId, '/like/').concat(user.id), function(data){
            data.forEach(like => {
                var btnLike = $('#like-'.concat(like.comment_id));
                btnLike.removeClass('main-text-hover');
                btnLike.addClass('blue-text-hover');
                btnLike.text('Bỏ Thích');
            });
        });
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
        <div class="row" style="margin: 0px !important;">
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
                            <div style="width: 90%; border-bottom: 1px solid #EEEEEE; padding-bottom: 10px;">
                                <div class="d-flex justify-content-between">
                                    <b>{{$comment->user->name}}</b>
                                    <b id="like-count-{{$comment->id}}" style="margin: 0px; color: blue;">
                                            @if ($comment->like_count > 0)
                                                {{$comment->like_count}} Like
                                            @endif
                                        </b>                                                                                    
                                </div>
                                <p class="secondary-text" style="margin: 5px 0px 0px 0px;">{{$comment->created_at}}</p>
                                <p class="main-text" style="margin: 5px 0px 0px 0px;">{{$comment->content}}</p>
                                <div class="d-flex">
                                    <b id="like-{{$comment->id}}" class="button main-text-hover" style="margin-right: 30px" onclick="likeClicked({{$comment->id}})">Thích</b>
                                    <b id="reply-{{$comment->id}}" class="button main-text-hover" onclick="replyClicked({{$comment->id}})">Trả lời</b>
                                </div>
                                <button id="loading-reply-{{$comment->id}}" class="btn btn-primary hidden" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Loading...                        
                                </button>
                                <div id="reply-zone-{{$comment->id}}" class="row hidden" style="width: 100%; padding-left: 15px !important; box-sizing:border-box !important; margin: 0px !important; border-top: 1px solid #E0E0E0;">
                                    <p id="old-replies-{{$comment->id}}" class="button secondary-text-hover hidden" style="width: 100%; margin-bottom: 0px;" onclick="oldReplies({{$comment->id}})">Trả lời cũ hơn</p>
                                    <div id="child-replies-{{$comment->id}}" style="width: 100%;"></div>
                                    <textarea name="" id="text-arera-reply-{{$comment->id}}" cols="1000" rows="2" style="margin-top: 10px;"></textarea>
                                    <button id="btn-reply-{{$comment->id}}" class="btn btn-primary" style="margin-top: 10px;" onclick="replyComment({{$comment->id}})">Trả lời</button>
                                    <button id="repling-{{$comment->id}}" class="btn btn-primary hidden" style="margin-top: 10px;" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Đang trả lời...                        
                                    </button>
                                </div>
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
