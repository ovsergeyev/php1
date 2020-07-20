<?php

//С помощью цикла while вывести все числа в промежутке от 0 до 100, которые делятся на 3 без остатка.

$counter = 0;

while($counter <= 100){
    $counter++;
    if($counter%3 === 0){
        echo $counter;
    }
}