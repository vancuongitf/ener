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

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote-bs4.js"></script>
</head>

<body style="min-width: 1000px;">
    @include('admin.widget.header')
    <div class="row row-eq-height content-full-size" style="padding: 0px; margin: 0px;">
        <div class="col-3 content-full-size" style="padding: 0px; margin: 0px;">
    @include('admin.widget.side-bar')
        </div>
        <div class="col-9 content-full-size" style="padding: 0px; margin: 0px;">
            @yield('content')
        </div>
    </div>
    <script>
        $("#viewHTML").click( function() {
            var markupStr = $('#summernote').summernote('code');
            $('#view').html(markupStr);
        }
        );
        $("#btn-insert-iamge").click( function() {
            var imageTagStart = "<div style=\"text-align:center;\"><img style=\"max-width: 100%; display: block; margin-left: auto; margin-right: auto;\" src=\"";
            var htmlContent = imageTagStart.concat($("#image-address").val()).concat("\"><i style=\"color: blue; font-size: 15px;\">").concat($("#image-description").val()).concat("</i></div><br>")
            var markupStr = $('#summernote').summernote('code');
            $("#summernote").summernote('code', markupStr.concat(htmlContent));
        });
        $("#summernote").summernote({
              placeholder: 'Input post\' content',
              tabsize: 2,
              height: 500
         });
        $('#title').keyup(function() {
            // var markupStr = $('#summernote').summernote('code');
            // $('#view').html(markupStr);
            console.log($('#title').val());
            $('#route').val(genUrl($('#title').val()));        
        });
        function genUrl(alias) {
            var str = alias;
            str = str.toLowerCase();
            str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
            str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
            str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
            str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
            str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
            str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
            str = str.replace(/đ/g,"d");
            str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g," ");
            str = str.replace(/ + /g," ");
            str = str.trim();
            while(str.includes("  ") > 0) {
                str = str/replace("  ", " ");
            }
            while(str.includes(" ") > 0) {
                str = str.replace(" ","-");
            }
            return str.concat(".html");
        }
    </script>
</body>

</html>