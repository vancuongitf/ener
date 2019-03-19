@extends('layouts.admin')
<style type="text/css">
    .row div {
        /* border-bottom: 1px solid black; */
    }
</style>

@section('content')
<div class="card card-default" style="margin: 20px;">
    <div class="card-header">
        <div class="d-flex justify-content-between" style="width:100%">
            <div style="font-size: 2rem">Post Tags</div>
            <button class="btn btn-primary" style="text-align:center;" onclick="openCreateTag()">Create New Post</button>
        </div>
    </div>
    <div class="card-body">
        @foreach ($tags as $tag)
        <div class="row" style="margin-bottom: 20px; border: 1px solid #EEEEEE; box-sizing:border-box;">
            <div class="col-4">
                <a class="main-text-hover" href="{{url('admin/tag/level1/' . $tag->route)}}">{{$tag->name}}</a>
            </div>
            <div class="col-8">
                @foreach ($tag->childs() as $child1)
                <div class="row" style="border-bottom: 1px solid #EEEEEE;">
                    <div class="col-6" style="border-left: 1px solid #EEEEEE;">
                        <a class="main-text-hover" href="{{url('admin/tag/level2/' . $child1->route)}}">{{$child1->name}}</a>
                    </div>
                    <div class="col-6" style="border-left: 1px solid #EEEEEE;">
                        @foreach ($child1->childs() as $child2)
                        <div class="row" style="padding-left: 20px;">
                            <a class="main-text-hover" href="{{url('admin/tag/level3/' . $child2->route)}}">{{$child2->name}}</a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection