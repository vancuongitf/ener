@extends('layouts.admin') 
@section('script')
    <script>
        function openCreatePost() {
                window.location.href = "/admin/post/create";
        }
        function deletePost($id) {
            $.ajax({
                type: 'DELETE',
                url:  "{{url('/api/admin/post/')}}".concat("/", $id),
                success: function( msg ) {
                    var objJSON = JSON.parse(msg);
                    if (objJSON.status == 'success') {
                        $('#post-'.concat($id)).remove();
                    }
                }
            });
        }

        function publishNow($id) {
            $.ajax({
                type: 'PUT',
                url:  "{{url('/api/admin/post/publish/')}}".concat("/", $id),
                crossDomain: true,
                xhrFields: { 
                    withCredentials: true
                },
                success: function( msg ) {
                    var objJSON = JSON.parse(msg);
                    if (objJSON.status == 'success') {
                        $.alert({
                            title: 'Notification!',
                            content: 'This post was published!',
                        });
                        $('#btnPublish-'.concat($id)).hide();
                    } else {
                        $.alert({
                            title: 'Error!',
                            content: 'Publish fail, please try again!',
                        });
                    }                   
                },
                error:function (xhr, ajaxOptions, thrownError) {
                    $.alert({
                        title: 'Error!',
                        content: 'Publish fail, please try again!',
                    });
                }
            });
        }
    </script>
@endsection
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
                    <th>Publish Now</th>
                    <th>Delete</th>
                </tr>
                @foreach ($posts as $post)
                <tr id="{{'post-' . $post->id}}" style="min-width: 700px;">
                    <td>{{$post->id}}</td>
                    <td style="max-width: 500px;"><a href="{{"admin/post/info/" . $post->id}}">{{$post->name}}</a></td>
                    <td><a href="{{url('admin/post/' . $post->id . '/tags')}}">View</a></td>
                    <td>{{$post->created_at}}</td>
                    <td>{{$post->view_count}}</td>
                    <td><input type="checkbox" style="width:30px; height:30px;" {{$post->isHot()}}></td>
                    <td><input type="checkbox" style="width:30px; height:30px;" {{$post->isHightLight()}}></td>
                    <td>
                        @if ($post->is_published == 0)
                            <button id="btnPublish-{{$post->id}}" class="btn btn-primary" onclick="publishNow({{$post->id}})">Publish now</button>                            
                        @endif
                    </td>
                    <td><button class="btn btn-primary" onclick="deletePost({{$post->id}})">Delete</button></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</div>
@endsection