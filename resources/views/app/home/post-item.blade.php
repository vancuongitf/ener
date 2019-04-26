<div class="col-sm-12 col-md-6 col-lg-6" style="padding: 0px; margin: 0px;">
    <div style="width: 100%; padding: 10px;">
        @if ($post->image)
        <div class="image-wrapper">
            <img src="{{ url('file_storage/' . $post->image) }}" alt="{{ $post->name }}">
        </div>
    @endif
    <a class="remove-text-decoration" href="{{url('post/' . $post->route)}}"><h3 class="main-text ellipse-3" style="text-align: center;">{{$post->name}}</h3></a>
    <p class="ellipse-1 secondary-text" style="text-align: center">{{date("d-m-Y", strtotime($post->created_at))}} | {{$post->view_count}} Lượt xem</p>
    <p class="ellipse-3">{{$post->description}}</p>
    </div>
</div>
