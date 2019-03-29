<?php

namespace App\Model\Util;

use Illuminate\Database\Eloquent\Model;

class IntSet extends Model {
    private $array = array();

    public function add($int) {
        if (!in_array($int, $this->array)) {
            array_push($this->array, $int);
        }
    }

    public function addArray($array) {
        foreach ($array as $int) {
            $this->add($int);
        }
    }

    public function getArray() {
        return $this->array;
    }

    public function count() {
        return count($this->array);
    }

    public function getQueryString() {
        $str = json_encode($this->array);
        $str = str_replace('[','(', $str);
        $str = str_replace(']',')', $str);
        return $str;
    }
}
