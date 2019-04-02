<?php
    $i = 0;
?>
<div>
    <div style="padding-bottom: 5px; border-bottom: 2px solid black;">
        <a style="text-decoration:none;" href="{{ url('/' . $category->route) }}">
            <h3 class="main-text-hover background-orange" style="padding: 5px 10px; margin: 0px;">{{$category->name}}</h3>
        </a>
    </div>
    <div style="padding: 10px;">
        @foreach ($category->posts as $post)
            @if ($i==0)
                <div style="border-bottom: 1px solid #BDBDBD">
                    @if ($post->image)
                        <div class="image-wrapper">
                            <img src="{{ url('file_storage/' . $post->image) }}" alt="{{$post->name}}">
                        </div>
                    @endif
                    <a style="text-decoration:none;" href="{{ url('post/' . $post->route) }}"><h4 class="main-text-hover ellipse">{{ $post->name }}</h4></a>                    
                    <p class="secondary-text" style="margin: 0px;">{{ $post->created_at }}</p>
                    <i class="main-text ellipse">{{ $post->description }}</i>
                    <div style="height:10px; width:1px;"></div>
                </div>
            @else
                <div style="border-bottom: 1px solid #BDBDBD; margin: 10px 0px;">
                    @if ($post->image)
                        <div style="width: 160px; float: left; margin-right: 10px; margin-bottom:10px;">
                            <div class="image-wrapper">
                                <img src="{{ url('file_storage/' . $post->image) }}" alt="{{$post->name}}">
                            </div>
                        </div>
                    @endif
                    <a style="text-decoration:none;" href="{{ url('post/' . $post->route) }}"><b class="main-text-hover ellipse" style="max-lines: 2;">{{ $post->name }}</b></a>                    
                    <p class="secondary-text">{{ $post->created_at }}</p>
                    <div class="clear"></div>
                </div>
            @endif
            <?php
                $i++;
            ?>
        @endforeach
    </div>
</div>
