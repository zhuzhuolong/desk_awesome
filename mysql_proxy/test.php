<?php

function test (&$array): array
{
    return $array = [1,2,3];
}

$a = 1100101;
$b = 0101111;

var_dump($a | $b);