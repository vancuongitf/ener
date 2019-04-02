@extends('layouts.app') 
<style type="text/css">
    
</style>
@section('page-title')

@endsection

@section('left-zone')

@endsection

@section('content')
<div class="col-lg-9" style="margin: 0px; padding: 0px;">
    <div class="row row-eq-height" style="box-sizing:border-box; padding: 10px; margin: 0px;">
        <h4 style="border-bottom: 2px solid black; width: 100%;">
            <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route) }}">{{$tag1->name}}</a>
            @if ($tag2)
                >   
                <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route . "/" . $tag2->route) }}">{{$tag2->name}}</a>                
                @if ($tag3)
                    > 
                    <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route . "/" . $tag2->route . "/" . $tag3->route) }}">{{$tag3->name}}</a>                
                @endif
            @endif
        </h4>
        @if ($categoryPosts) 
            <div style="width: 100%">
                @foreach ($categoryPosts->posts as $post)
                    @include('app.category.post-item', ['post' => $post])
                @endforeach
            </div>
        @endif
    </div>
</div>
<div class="col-lg-3">

</div>
@endsection
