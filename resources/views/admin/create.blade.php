@extends('layouts.admin') 
@section('content')
<div class="row" style="font-size: 1.5rem; padding: 0px; margin: 0px;">
    <div class="col-8">
        <div class="card card-default" style="margin: 20px;">
            <div class="card-header">Create New Post</div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input id="title" name="title" type="text" placeholder="Input post's title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input id="route" name="route" type="text" placeholder="Input post's route" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input id="image" type="file" name="image" accept="image/*" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <textarea name="description" id="description" cols="30" rows="5" placeholder="Input post's description" class="form-control"
                            required></textarea> </div>
                    <div class="form-group">
                        <textarea name="summernote" id="summernote" cols="30" rows="10" class="form-control" placeholder="Input post's content" required></textarea>
                    </div>

                    <div class="form-group">
                        <button id="viewHTML" class="btn btn-primary">View content HTML</button>
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Create" class="form-control">
                    </div>
                </form>

                <div id="view">

                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-4">
        <div class="card card-default" style="margin: 20px;">
            <div class="card-header">Content Image</div>
            <div class="card-body">
                <div class="form-group">
                    <input type="text" name="image-address" id="image-address" placeholder="Image's address" class="form-control">
                </div>
                <div class="form-group">
                    <input type="text" name="image-description" id="image-description" placeholder="Image's description" class="form-control">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" id="btn-insert-iamge">Insert Image</button>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection