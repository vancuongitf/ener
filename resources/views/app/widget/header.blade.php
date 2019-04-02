<?php
    use App\Model\Tag\TagLevel1;
    use App\Model\Tag\TagLevel2;
    use App\Model\Tag\TagLevel3;
    $tags = TagLevel1::all();
    foreach ($tags as $tag) {
        $tag2s = TagLevel2::where('tag_level_1_id', $tag->id)->get();
        foreach($tag2s as $tag2) {
            $tag3s = TagLevel3::where('tag_level_2_id', $tag2->id)->get();
            $tag2->childs = $tag3s;
        }
        $tag->childs = $tag2s;
    }
?>
<nav class="navbar navbar-expand-lg navbar-light background-black sticky-top">
    <a class="navbar-brand" style="margin-right: 50px;" href="/">
        <img style="width: 38px; height: 38px;" src="{{ url('file/icon-home-white.png') }}" alt="" srcset="">
    </a>
    <button class="navbar-toggler" type="button" style="padding: 0px; margin: 0px; border: none; margin-right: 5px;" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <img style="width: 50px; height: 50px;" src="{{ url('file/icon-menu-white.png') }}" alt="">
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            @foreach ($tags as $tag) @if (count($tag->childs) > 0)
            <li class="nav-item dropdown" style="position: static;">
                <a class="nav-link dropdown-toggle text-bg-black" href="" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 50px;">{{$tag->name}}</a>
                <div class="dropdown-menu background-black" style="width:100%; border: none; border-radius: 0px; padding: 0px; margin: 0px;">
                    <div class="row background-black" style="padding: 0px 15px;">
                        <div class="col-12">
                            <a class="dropdown-item text-bg-black" style="padding: 3.75px 0px; background-color:transparent !important;" href="{{url($tag->route)}}">{{$tag->name}}</a>
                        </div>
                        @foreach ($tag->childs as $tag2)
                        <div class="col-lg-6">
                            <a class="dropdown-item text-bg-black" style="background-color:transparent !important;" href="{{url($tag->route . '/' . $tag2->route)}}">{{$tag2->name}}</a>
                        </div>
                        <div class="col-lg-6">
                            @foreach ($tag2->childs as $tag3)
                            <a class="dropdown-item text-bg-black" href="{{url($tag->route . '/' . $tag2->route . '/' . $tag3->route)}}" style="background-color:transparent !important; margin: 0px 15px;">{{$tag3->name}}</a>                                @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </li>
            @else
            <li class="nav-item" style="margin-right: 50px;">
                <a class="nav-link text-bg-black" s href="{{url($tag->route)}}">{{$tag->name}}</a>
            </li>
            @endif @endforeach
        </ul>
    </div>
</nav>
