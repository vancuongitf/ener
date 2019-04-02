<style type="text/css">
    .image-zone {
        width: 320px;
        height: 180px;
        margin-right: 10px;
        float: left;
    }
    .post-title-lg {
        display: block;
    }
    .post-title-sm {
        display: none;
    }
    @media (max-width: 767px) {
        .image-zone {
            width: 160px;
            height: 90px;
            margin-right: 10px;
            float: left;
        }
        .post-title-lg {
            display: none;
        }
        .post-title-sm {
            display: block;
        }
    }
</style>
<div class="test" style="margin: 0px; padding: 0px; margin-bottom: 20px;">
    @if ($post->image)
        <div class="image-zone">
            <div class="image-wrapper">
                <img src="{{ url('file_storage/' . $post->image) }}" alt="{{ $post->name }}">
            </div>
        </div>
        <a href=" {{ url('post/' . $post->route) }} ">
            <h3 class="post-title-lg">{{ $post->name }}</h3>
            <b class="post-title-sm">{{ $post->name }}</b>
        </a>
        <div class="clear">

        </div>
    @endif
</div>