@extends('layouts.app') 
<style type="text/css">
    .post-content {
    }

    .post-content img {
        max-width: 100% !important; 
        display: block !important; 
        margin-left: auto !important; 
        margin-right: auto !important;
    }
</style>
@section('content')
<div class="post-content" style="padding: 15px;">
    <h1><b>{{$post->name}}</b></h1>
    <p class="secondary-text">{{$post->getPostedTime()}}</p>
    <b>{{$post->description}}</b> 
    {!!$post->content!!}
</div>
@endsection
