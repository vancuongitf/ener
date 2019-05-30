@extends('layouts.admin')
@section('style')
    <style type="text/css">
        .form-control {
            height: auto;
        }
    </style>
@endsection
@section('script')
    <script>
        function btnEditClicked() {
            $('#tag_info_name').hide();
            $('#tag_info_route').hide();
            $('#new_tag_info_name').show();
            $('#new_tag_info_route').show();
            $('#btn_submit').show();
        }

        function btnDeleteClicked() {
            $.alert({
                title: 'Notification',
                content: 'Do you want delete this tag?',
                buttons: {
                    heyThere: {
                        text: 'OK', // text for button
                        btnClass: 'btn-blue', // class for the button
                        keys: ['enter'], // keyboard event for button
                        isHidden: false, // initially not hidden
                        isDisabled: false, // initially not disabled
                        action: function (heyThereButton) {
                            var apiUrl = '{{ "/api/admin/tag/" . $tag->level . "/" . $tag->id}}';
                            $.ajax({
                                url: apiUrl,
                                type: 'DELETE',
                                success: function(result) {
                                    var response = JSON.parse(result);
                                    if (response.status = "success") {
                                        myAlert('Notification!', 'Delete tag success!', function() {
                                            window.location.href = '{{ url("admin/tag") }}';
                                        });
                                    } else {
                                        myAlert('Thông báo!', 'Xoá tag thất bại. Vui lòng thử lại sau.', function() {});
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    myAlert('Notification!', 'Delete tag fail. Please try again!', function() {});
                                }
                            });
                        }
                    },
                    cancel: function () {
                    },
                }
            });
        }
    </script>
@endsection
@section('content')
    <div class="card card-default" style="margin: 20px;">
        <div class="card-header d-flex justify-content-between">
            <div>Tag Info</div>
            <div>
                <div class="btn btn-primary" onclick="btnEditClicked()">Edit</div>
                <div class="btn btn-primary" onclick="btnDeleteClicked()">Delete</div>
            </div>
        </div>
        <div class="card-body">
            @if($errors->first('message'))
                <p class="red-text" style="font-size: 1rem;">{{$errors->first('message')}}</p>
            @endif
            <div id="tag_info_name" class="form-control">Name: <b>{{$tag->name}}</b></div>
            <div id="tag_info_route" class="form-control margin-top-10">Route: <b>{{$tag->route}}</b></div>
            <form action="" method="POST">
                @csrf
                <?php
                    $parent = $tag->getParent();
                ?>
                <input type="hidden" name="id" value="{{$tag->id}}">
                <input type="hidden" name="level" value="{{$tag->level}}">
                @if (isset($parent->id))
                    <input type="hidden" name="parent_id" value="{{$parent->id}}">                    
                @endif
                <input id="new_tag_info_name" name="name" class="form-control margin-top-10" type="text" value="{{$tag->name}}" style="display:none;">
                @if ($errors->first('name'))
                    <p class="red-text" style="font-size: 1rem;">Tag's name is required!</p>
                @endif
                @if ($errors->first('name-exist'))
                    <p class="red-text" style="font-size: 1rem;">Tag's name is already exists!</p>
                @endif
                <input id="new_tag_info_route" name="route" class="form-control margin-top-10" type="text" value="{{$tag->route}}" style="display:none;">            
                @if ($errors->first('route'))
                    <p class="red-text" style="font-size: 1rem;">Tag's route is required!</p>
                @endif
                @if ($errors->first('route-regex'))
                    <p class="red-text" style="font-size: 1rem;">Tag's route is incorrect format!</p>
                @endif
                @if ($errors->first('route-exist'))
                    <p class="red-text" style="font-size: 1rem;">Tag's route is already exists!</p>
                @endif
                <div class="form-control margin-top-10">Level: <b>{{$tag->level}}</b></div>
                @if ($tag->getParent())
                    <div class="form-control margin-top-10">
                        Parent: <a href="{{url("admin/tag/{$parent->level}/{$parent->id}")}}"><b>{{$parent->name}}</b></a>
                    </div>                
                @endif
                <?php
                    $childs = $tag->getChilds();
                    $count = count($childs);
                ?>
                @if ($childs)
                    <div class="form-control margin-top-10">
                        Childs: 
                        @for ($i = 0; $i < $count; $i++)
                            <?php
                                if ($i == $count -1) {
                                    echo '<a href="' . url("admin/tag/{$childs[$i]->level}/{$childs[$i]->id}") .'"><b>' . $childs[$i]->name . '.</b></a>';
                                } else {
                                    echo '<a href="' . url("admin/tag/{$childs[$i]->level}/{$childs[$i]->id}") .'"><b>' . $childs[$i]->name . ', </b></a>';
                                }
                            ?>
                        @endfor
                    </div>
                @endif
                <button id="btn_submit" class="btn btn-primary margin-top-10" type="submit" style="display:none;">Update</button>
            </form>

        </div>
    </div>
@endsection
