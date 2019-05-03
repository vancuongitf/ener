@extends('layouts.admin') 
@section('script')
    <script>
        function nextPage() {
            window.location.href = "{{ url('admin/users/' . ($page + 1)) }}";
        }

        function previousPage() {
            window.location.href = "{{ url('admin/users/' . ($page - 1)) }}";            
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
                <div>User List</div> 
            </div>
        </div>
        <div class="card-body" style="box-sizing:border-box !important;">
            <table class="border-table" style="width: 100%;">
                <tr>
                    <th style="text-align:center; max-width: 80px;">STT</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th style="min-width:200px;text-align:center;">Email</th>
                </tr>
                @foreach ($users as $user)
                <tr id="{{'post-' . $user->id}}">
                    <td style="text-align:center; max-width: 80px;">{{$stt++}}</td>
                    <td style="height:100px; text-align:center;">{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                </tr>
                @endforeach
                @for ($i = count($users); $i < 30; $i++)
                    <tr style="height:100px;">
                        <td style="text-align:center; max-width: 80px;">{{$stt++}}</td>
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