<?php

// checks if one value in a given array is empty
function check_for_empty_values($value_array)
{
    $is_empty = False;

    foreach ($value_array as $value) {
        if (empty($value)) {
            $is_empty = True;
        }
    }
    return $is_empty;
}
