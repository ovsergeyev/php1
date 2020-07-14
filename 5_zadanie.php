<?php

$a = 1;
$b = 2;

//Деструктуризация

//php < 7.1
list($a, $b) = [$b, $a];

//php 7.1
//[$a, $b] = [$b, $a];

echo $a;
echo $b;