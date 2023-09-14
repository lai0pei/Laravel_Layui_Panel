<?php


function idAsKey($array, $key = 'id'): array
{
    $res = [];
    if (!isset($array)) {
        return $res;
    }
    foreach ($array as $v) {
        $res[$v[$key]] = $v;
    }

    return $res;
}


function encode($str)
{
    $rep = [];
}

