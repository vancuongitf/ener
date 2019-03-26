<?php
    use App\Model\Tag\TagLevel1;
    use App\Model\Tag\TagLevel2;
    use App\Model\Tag\Taglevel3;
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
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #E0E0E0;">
        <a class="navbar-brand" style="font-size: 2.5rem; margin-right: 50px;" href="">Trang Chủ</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                @foreach ($tags as $tag) 
                    @if (count($tag->childs) > 0)
                    <li class="nav-item dropdown" style="position: static;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 50px; font-size: 1.5rem;">{{$tag->name}}</a>
                        <div class="dropdown-menu" style="width:100%; border: none; border-radius: 0px; padding: 0px; margin: 0px; background-color: #F5F5F5">
                            <div class="row" style="padding: 0px 15px;">
                                <div class="col-12">
                                    <a class="dropdown-item header-text" style="padding: 3.75px 0px; background-color:transparent !important; font-size: 1.5rem;" href="{{url($tag->route)}}">{{$tag->name}}</a>
                                </div>
                                @foreach ($tag->childs as $tag2)
                                <div class="col-lg-6">
                                    <a class="dropdown-item header-text" style="background-color:transparent !important; font-size: 1.2rem;" href="{{url($tag->route . '/' . $tag2->route)}}">{{$tag2->name}}</a>
                                </div>
                                <div class="col-lg-6">
                                    @foreach ($tag2->childs as $tag3)
                                    <a href="{{url($tag->route . '/' . $tag2->route . '/' . $tag3->route)}}" class="dropdown-item header-text" style="background-color:transparent !important; font-size: 1.2rem; margin: 0px 15px;">{{$tag3->name}}</a>                                
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </li>
                    @else 
                    <li class="nav-item" style="margin-right: 50px; font-size: 1.5rem;">
                        <a class="nav-link" s href="{{url($tag->route)}}">{{$tag->name}}</a>
                    </li>
                    @endif 
                @endforeach
            </ul>
        </div>
    </nav>