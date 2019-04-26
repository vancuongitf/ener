@extends('layouts.app')
@section('meta-data')
    <meta property="og:image" content="{{url('file/ener.png')}}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="http://ener.vn"/>
    <meta property="og:title" content="Nơi Giúp Bạn Hoàn Thiện Bản Thân Mình"/>
    <meta property="og:description" content="skajdh skjdha sdkjasbd askdbas kdas dkjas dkas dkjas dkjas dkjas dj asjkd askjd askjd jkas dkjas dkjasd kasd kajs"/> 
@endsection
@section('include-script')

@endsection
@section('page-title')
    Ener.vn
@endsection
@section('left-zone')
    
@endsection
@section('top-logo')
    <div style="max-width: 100%;">
        <div style="padding-bottom: 30%; position: relative;">
            <img src="{{ url('/file/ener-cover.png') }}" style="width: 100%; height: 100%; position: absolute;">
        </div>
    </div>
@endsection
@section('high-light-zone')
    @include('app.dynamic-views.high-light-zone')
@endsection
{{-- @section('content')
    <div class="row" style="margin: 0px;">
        <div class="col-lg-9">
            <div class="row row-eq-height" style="box-sizing:border-box;">
                @foreach ($categories as $category)
                    <div class="col-lg-6" style="padding: 10px;">
                        @include('app.home.category', ['category' => $category])
                    </div>
                @endforeach
        </div>
        </div>
        <div class="col-lg-3 ads-side-zone" style="padding: 10px; margin: 0px;">

        </div>
    </div>
@endsection --}}
@section('home-content')
    <div class="row border-box">
        <div class="ads-side-zone col-lg-2">
        </div>
        <div class="col-md-12 col-lg-7 border-box">
            <div class="row" style="padding: 0px; margin: 0px;">
                @foreach ($posts as $post)
                    @include('app.home.post-item', ['post' => $post])
                @endforeach
            </div>
            <div>

            </div>
        </div>
        <div class="col-md-12 col-lg-3 border-box">
            
        </div>
    </div>
@endsection
