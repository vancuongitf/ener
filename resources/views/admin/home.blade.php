@extends('layouts.admin') 
@section('content')
<div class="content-full-size">
    <div class="card card-default" style="margin: 20px;">
        <div class="card-header" style="font-size: 2rem">Post List</div>
        <div class="card-body">
            <table class="border-table">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
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