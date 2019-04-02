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
        <div style="padding-bottom: 5px; border-bottom: 2px solid black; width: 100%; margin-bottom: 20px;">
            <h3 class="main-text-hover background-orange" style="padding: 5px 10px; margin: 0px;">{{$tag1->name}}</h3>
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
