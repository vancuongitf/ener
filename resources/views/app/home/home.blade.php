@extends('layouts.app')
@section('include-script')
<script>
        $(document).ready( function() {
            var string = '<p><iframe frameborder="0" src="//www.youtube.com/embed/3azFLJsOJlac" width="640" height="360" class="note-video-clip"></iframe><br></p>';
            console.log(string.replace(/<iframe(.*)iframe>/g,'cuong'));
        });
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="row row-eq-height" style="box-sizing:border-box;">
                @foreach ($categories as $category)
                    <div class="col-lg-6" style="padding: 10px;">
                        @include('app.home.category', ['category' => $category])
                    </div>
                @endforeach
        </div>
        </div>
        <div class="col-lg-3">

        </div>
    </div>
@endsection
