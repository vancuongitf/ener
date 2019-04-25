@extends('layouts.app')
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
@section('content')
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
@endsection
