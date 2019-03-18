@extends('layouts.admin')
<style type="text/css">
    .row div {
        /* border-bottom: 1px solid black; */
    }
</style>
@section('content')
<div style="margin: 20px; padding: 20px 20px 0px 20px; border: 1px solid #EEEEEE;">
    <a class="btn btn-primary" href="{{url('admin/tag/create')}}" style="text-decoration:none; margin-bottom: 20px;">Create New Tag</a>
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
@endsection