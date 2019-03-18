<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{--
    <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/text.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous"> d

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</head>

<body style="min-width: 1000px;">
    <script>
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
    @include('admin.widget.header')
    <div class="row row-eq-height content-full-size" style="padding: 0px; margin: 0px;">
        <div class="col-3 content-full-size" style="padding: 0px; margin: 0px;">
    @include('admin.widget.side-bar')
        </div>
        <div class="col-9 content-full-size" style="padding: 0px; margin: 0px;">
            @yield('content')
        </div>
    </div>
</body>

</html>