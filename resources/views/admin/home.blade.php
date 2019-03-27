@extends('layouts.admin') 
@section('script')
    <script>
        function openCreatePost() {
            window.location.href = "/admin/post/create";
        }
        function notPublishYet() {
            window.location.href = "/admin/notpublish"
        }
        function deletePost($id) {
            $.alert({
                title: 'Delete confirm!',
                content: 'Do you want delete this post now?',
                buttons: {
                    heyThere: {
                        text: 'OK', // text for button
                        btnClass: 'btn-blue', // class for the button
                        keys: ['enter'], // keyboard event for button
                        isHidden: false, // initially not hidden
                        isDisabled: false, // initially not disabled
                        action: function(heyThereButton){
                            $.ajax({
                                type: 'DELETE',
                                url:  "{{url('/api/admin/post/')}}".concat("/", $id),
                                success: function( msg ) {
                                    var objJSON = JSON.parse(msg);
                                    if (objJSON.status == 'success') {
                                        $.alert({
                                            title: 'Notification!',
                                            content: 'This post was deleted!',
                                        });
                                        $('#post-'.concat($id)).remove(); 
                                    } else {
                                        $.alert({
                                            title: 'Error!',
                                            content: 'Delete post fail, please try again!',
                                        }); 
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    $.alert({
                                        title: 'Error!',
                                        content: 'Delete post fail, please try again!',
                                    });
                                }
                            });
                        }
                    },
                    cancel: function () {
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
        function nextPage() {
            window.location.href = "{{ url('admin/' . ($page + 1)) }}";
        }

        function previousPage() {
            window.location.href = "{{ url('admin/' . ($page - 1)) }}";            
        }
    </script>
@endsection
<?php
    $stt = 1;
?>
@section('content')
<div class="content-full-size" style=" box-sizing:border-box;">
    <div class="card card-default" style="margin: 20px; box-sizing:border-box !important;">
        <div class="card-header" style="font-size: 2rem"> 
            <div class="d-flex justify-content-between" style="width:100%">
                <div>Post List</div> 
                <div>
                    @if ($showNotPublishButton)
                        <button class="btn btn-primary" style="text-align:center;" onclick="notPublishYet()">Not Publish Yet</button>                        
                    @endif
                    <button class="btn btn-primary" style="text-align:center;" onclick="openCreatePost()">Create New Post</button>
                </div>
            </div>
        </div>
        <div class="card-body" style="box-sizing:border-box !important;">
            <table class="border-table" style="width: 100%;">
                <tr>
                    <th style="text-align:center; max-width: 80px;">STT</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Tag</th>
                    <th style="min-width:200px;text-align:center;">Created At</th>
                    <th>View Count</th>
                    <th>Hot</th>
                    <th>High Light</th>
                    <th style="text-align:center;">Publish Now</th>
                    <th style="text-align:center;">Delete</th>
                </tr>
                @foreach ($posts as $post)
                <tr id="{{'post-' . $post->id}}">
                    <td style="text-align:center; max-width: 80px;">{{$stt++}}</td>
                    <td style="height:100px;">{{$post->id}}</td>
                    <td><a href="{{"admin/post/info/" . $post->id}}">{{$post->name}}</a></td>
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
                @for ($i = count($posts); $i < 30; $i++)
                    <tr style="height:100px;">
                        <td style="text-align:center; max-width: 80px;">{{$stt++}}</td>
                        <td></td>
                        <td></td>                      
                        <td></td>                      
                        <td></td>                      
                        <td></td>                      
                        <td></td>                      
                        <td></td>                      
                        <td></td>                      
                        <td></td>                      
                    </tr>
                @endfor
            </table>
            <div class="d-flex justify-content-center" style="padding: 10px;">
                <button class="btn btn-primary" onclick="previousPage()" @if ($page < 2) disabled @endif>Prev</button>
                <p style="margin: auto 20px;">Page: {{$page}}</p>
                <button class="btn btn-primary" onclick="nextPage()" @if (!$haveNextPage) disabled @endif>Next</button>
            </div>
        </div>
    </div>

</div>
@endsection