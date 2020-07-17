<?php

$a = 12;
$b = 25;

if($a >= 0 && $b >= 0){
    echo $a - $b;
} elseif($a < 0 && $b < 0){
    echo $a * $b;
} else {
    echo $a + $b;
}