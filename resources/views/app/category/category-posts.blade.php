@extends('layouts.app') 
<style type="text/css">
    .title-lg {
        display: block;
    }
    .title-sm {
        display: none;
    }
    @media (max-width: 767px) {
        .title-lg {
            display: none;
        }
        .title-sm {
            display: block;
        }
    }
</style>
@section('page-title')
    @if ($tag2 != null)
        @if ($tag3 != null)
            {{ $tag3->name }}
        @else
            {{ $tag2->name }}
        @endif
    @else
        {{ $tag1->name }}
    @endif
@endsection

@section('left-zone')

@endsection

@section('content')
<div class="col-lg-9" style="margin: 0px; padding: 0px;">
    <div class="row row-eq-height" style="box-sizing:border-box; padding: 10px; margin: 0px;">
        <div style="padding-bottom: 5px; border-bottom: 2px solid black; width: 100%; margin-bottom: 20px;">
        <div class="title-lg">
            <h3 class="ellipse-1 background-orange" style="padding: 5px 10px; margin: 0px;">
                <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route) }}">{{$tag1->name}}</a> 
                @if ($tag2 != null)
                    >
                    <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route . '/' . $tag2->route) }}">{{$tag2->name}}</a>  
                    @if ($tag3 != null)
                        >
                        <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route . '/' . $tag2->route . '/' . $tag3->route) }}">{{$tag3->name}}</a>  
                    @endif
                @endif
            </h3>
        </div>
        <div class="title-sm">
            <b class="ellipse-1 background-orange" style="padding: 5px 10px; margin: 0px;">
                <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route) }}">{{$tag1->name}}</a> 
                @if ($tag2 != null)
                    >
                    <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route . '/' . $tag2->route) }}">{{$tag2->name}}</a>  
                    @if ($tag3 != null)
                        >
                        <a class="main-text-hover remove-text-decoration" href="{{ url($tag1->route . '/' . $tag2->route . '/' . $tag3->route) }}">{{$tag3->name}}</a>  
                    @endif
                @endif
            </b>
        </div>  
    </div>
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
