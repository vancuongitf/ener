<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Tag\TagLevel1;
use App\Model\Tag\TagLevel2;
use App\Model\Tag\Taglevel3;
use App\Model\Response\TextResponse;

class TagController extends Controller {
    public function getTags() {
        $tags = TagLevel1::all();
        foreach ($tags as $tag) {
            $tag2s = TagLevel2::where('tag_level_1_id', $tag->id)->get();
            foreach($tag2s as $tag2) {
                $tag3s = TagLevel3::where('tag_level_2_id', $tag2->id)->get();
                $tag2->childs = $tag3s;
            }
            $tag->childs = $tag2s;
        }
        $str = "";
        foreach ($tags as $tag) {
            $html = "<li class=\"nav-item dropdown\" style=\"position: static;\">";
            $html = $html . "<a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdownMenuLink\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">" . $tag->name . "</a>";
            $html = $html . "<div class=\"dropdown-menu\" style=\"width:100%; border: none; border-radius: 0px; padding: 0px; margin: 0px;\">";
            $html = $html . '<div class="row" style="padding: 0px 15px;">';
            $html = $html . '<div class="col-12">';
            $html = $html . '<a class="dropdown-item" style="padding: 3.75px 0px;" href="' . $tag->route . '">' . $tag->name . '</a>';
            $html = $html . '</div>';
            foreach ($tag->childs as $tag2) {
                $html = $html . '<div class="col-lg-6">';
                $html = $html . '<a class="dropdown-item" href="' . $tag->route . '/' . $tag2->route . '">' . $tag2->name . '</a>';
                $html = $html . '</div>';
                $html = $html . '<div class="col-lg-6">';
                foreach($tag2->childs as $tag3) {
                    $html = $html . '<a href="' . $tag->route . '/' . $tag2->route . '/' . $tag3->route . '" class="dropdown-item">' . $tag3->name . '</a>';
                }
                $html = $html . '</div>';
                $html = $html . '</div>';
                $html = $html . '</div>';
            }
            $html = $html . '</li>';
            $str = $str . $html;
        }
        return json_encode(new TextResponse([
            'text' => $str
        ]));
    }
}
