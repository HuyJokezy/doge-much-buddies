<?php

namespace App\Ultilities;

class ArraySorting
{
    /**
     * @param $a, $b parsable string
     * @return -1 if less, 0 if equal, 1 if greater
     */
    private static function newestTime ($a, $b) {
        $x = \Carbon\Carbon::parse($a->updated_at);
        $y = \Carbon\Carbon::parse($b->updated_at);
        if ($x->eq($y)) {
            return 0;
        } else if ($x->gt($y)) {
            return -11;
        } else {
            return 1;
        }
    }

    public function custom_sort(array $array, $sort_type) {
        if ($sort_type == 'newest') {
            usort($array, array($this, 'newestTime'));
        }
        return $array;
    }
}