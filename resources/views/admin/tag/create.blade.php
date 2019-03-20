@extends('layouts.admin') 
@section('script')
    <script>
        function showImageTo(input, $target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $($target).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).ready(function() {
            $('#level').change(function() {
                var level = $(this).children("option:selected").val();
                $('#level_1_parent').attr("disabled", "disabled");
                $('#level_2_parent').attr("disabled", "disabled");
                switch(level) {
                    case '1':
                        console.log($('#level_1_parent').find(":selected").text());
                        $('#level_1_parent').prop('selectedIndex',0);
                        $('#level_2_parent').prop('selectedIndex',0);                        
                        break;
                    
                    case '2':
                        $('#level_1_parent').removeAttr("disabled");
                        $('#level_2_parent').prop('selectedIndex',0);                        
                        break;

                    case '3':
                        $('#level_1_parent').removeAttr("disabled");
                        $('#level_2_parent').removeAttr("disabled");
                        break;
                }
            });
            $('#level_1_parent').change(function() {
                var selectedTag = $(this).children("option:selected").val();
                $('#level_2_parent').html("<option disabled selected value>Tag level 2 parent</option>");
                $.ajax({
                    type: "GET",
                    url: "/api/admin/tag/childs/1/".concat(selectedTag),                
                    success: function( msg ) {
                        var objJSON = JSON.parse(msg);
                        var htmlContent = "";
                        $('#level_2_parent').html("<option disabled selected value>Tag level 2 parent</option>");
                        objJSON.forEach(element => {
                            $('#level_2_parent').append(htmlContent.concat('<option value="', element.id, '">' , element.name, '</option>'));
                        });
                    }
                });
            });
        });
    </script>
@endsection
@section('content')
<div class="card card-default" style="margin: 20px;">
    <div class="card-header">
        Create New Tag
    </div>

    <div class="card-body">
        <form action="" method="POST">
            @csrf

            <div class="form-group">
            <input class="form-control" type="text" name="name" id="name" placeholder="Input tag's name" value="{{old('name')}}"> 
            @if ($errors->first('name'))
                <p class="red-text">Please fill tag's name!</p>
            @endif
            @if ($errors->first('name-exist'))
                <p class="red-text">{{$errors->first('name-exist')}}</p>
            @endif
            <input class="form-control" style="margin-top: 20px;" type="text" name="route" id="route" placeholder="Input tag's route" value="{{old('route')}}">                
            @if ($errors->first('route'))
                <p class="red-text">Please fill tag's route</p>
            @endif
            @if ($errors->first('route-exist'))
                <p class="red-text">{{$errors->first('name-exist')}}</p>
            @endif
                <select class="form-control" style="margin-top: 20px;" name="level" id="level">
                        <option disabled selected value>Tag Level</option>
                        <option value="1">Level 1</option>
                        <option value="2">Level 2</option>
                        <option value="3">Level 3</option>                    
                </select> 
                @if ($errors->first('level'))
                    <p class="red-text">Please choose tag's level!</p>
                @endif
                <select name="level_1_parent" id="level_1_parent" class="form-control" style="margin-top: 20px;" disabled>
                        <option disabled selected value>Tag level 1 parent</option>                    
                    @foreach ($taglv1s as $taglv1)
                        <option value="{{$taglv1->id}}">{{$taglv1->name}}</option>
                    @endforeach
                </select> 
                @if ($errors->first('level_1_parent'))
                    <p class="red-text">Please choose tag's level 1 parent!</p>
                @endif
                <select name="level_2_parent" id="level_2_parent" class="form-control" style="margin-top: 20px;" disabled>
                        <option disabled selected value>Tag level 2 parent</option>   
                </select> 
                @if ($errors->first('level_2_parent'))
                    <p class="red-text">Please choose tag's level 2 parent!</p>
                @endif
                <input type="submit" value="Create" class="btn btn-primary form-control" style="margin-top: 20px;">
            </div>
        </form>
    </div>
</div>
@endsection