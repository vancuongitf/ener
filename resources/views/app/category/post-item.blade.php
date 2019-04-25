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
    .createdTime {
        position: absolute; 
        left: 330px; 
        bottom: 0; 
        margin: 0px;
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
        .createdTime {
            left: 170px; 
        }
    }
</style>
<div style="margin: 0px; padding: 0px; margin-bottom: 20px; position: relative;">
    @if ($post->image)
        <div class="image-zone">
            <div class="image-wrapper">
                <img src="{{ url('file_storage/' . $post->image) }}" alt="{{ $post->name }}">
            </div>
        </div>
        <a class="remove-text-decoration main-text-hover" href=" {{ url('post/' . $post->route) }} ">
            <div class="post-title-lg">
                <h3 class="ellipse-3">{{ $post->name }}</h3>
            </div>
            <div class="post-title-sm">
                <b class="ellipse-3">{{ $post->name }}</b>
            </div>
        </a>
        @if ($post->description)
            <div class="post-title-lg">
                <i class="ellipse-3">{{ $post->description }}</i>
            </div>
        @endif
        <p class="secondary-text createdTime">{{ $post->created_at }}</p>                
        <div class="clear">

        </div>
    @else
        <a class="remove-text-decoration main-text-hover" href=" {{ url('post/' . $post->route) }} ">
            <div class="post-title-lg">
                <h3 class="ellipse-3">{{ $post->name }}</h3>
            </div>
            <div class="post-title-sm">
                <b class="ellipse-3">{{ $post->name }}</b>
            </div>
        </a>
        @if ($post->description)
            <div class="post-title-lg">
                <i class="ellipse-3">{{ $post->description }}</i>
            </div>
        @endif
        <p class="secondary-text">{{ $post->created_at }}</p>                
        <div class="clear">

        </div>
    @endif
</div>