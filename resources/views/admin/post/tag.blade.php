@extends('layouts.admin') 
@section('content')
<div style="padding: 20px; width: 100%; box-sizing: border-box;">
    <div class="card card-default">
        <div class="card-header">{{$post->name}}</div>
        <div class="card-body">
            @foreach ($tags as $tag)
                <div id="{{$tag->id}}" style="border: 1px solid black; padding: 5px;">
                    <button class="btn btn-primary" onclick="removePostTag({{$tag->id}})">Remove</button>
                    <a href="{{url($tag->getRoute())}}">{{$tag->getName()}}</a> 
                </div>
            @endforeach
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-6">
            <div class="card card-default">
                <div class="card-header">Add New Tag</div>
                <div class="card-body">
                    <form action="" method="post">
                        @csrf
                        @if ($errors->first('tag-exist'))
                            <p class="red-text">This tag is already exist!</p>
                        @endif
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        <select name="tag_level_1" id="tag_level_1" class="form-control">
                            <option disabled selected value>Tag Level 1</option>
                            @foreach ($tagLevel1s as $tagLevel1)
                                <option value="{{$tagLevel1->id}}">{{$tagLevel1->name}}</option>
                            @endforeach
                        </select>
                        @if ($errors->first('tag_level_1'))
                            <p class="red-text">Please choose tag level 1!</p>
                        @endif
                        <select name="tag_level_2" id="tag_level_2" class="form-control" style="margin-top:20px;">
                            <option disabled selected value>Tag Level 2</option>
                        </select>

                        <select name="tag_level_3" id="tag_level_3" class="form-control" style="margin-top:20px;">
                            <option disabled selected value>Tag Level 3</option>
                        </select>
                        <button type="submit" class="btn btn-primary form-control" style="margin-top:20px;">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection