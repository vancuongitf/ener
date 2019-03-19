@extends('layouts.admin') 
@section('content')
<div class="content-full-size">
    <div class="card card-default" style="margin: 20px;">
        <div class="card-header" style="font-size: 2rem">
            <div class="d-flex justify-content-between" style="width:100%">
                <div>Post List</div> 
                <button class="btn btn-primary" style="text-align:center;" onclick="openCreatePost()">Create New Post</button>
            </div>
        </div>
        <div class="card-body">
            <table class="border-table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Tag</th>
                    <th>Create At</th>
                    <th>View Count</th>
                    <th>Hot</th>
                    <th>High Light</th>
                    <th>Apply Change</th>
                    <th>Delete</th>
                </tr>
                @foreach ($posts as $post)
                <tr style="min-width: 700px;">
                    <td>{{$post->id}}</td>
                    <td style="max-width: 500px;">{{$post->name}}</td>
                    <td><a href="{{url('admin/post/' . $post->id . '/tags')}}">View</a></td>
                    <td>{{$post->created_at}}</td>
                    <td>{{$post->view_count}}</td>
                    <td><input type="checkbox" style="width:30px; height:30px;" {{$post->isHot()}}></td>
                    <td><input type="checkbox" style="width:30px; height:30px;" {{$post->isHightLight()}}></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</div>
@endsection