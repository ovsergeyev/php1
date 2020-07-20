<?php

//С помощью цикла do…while написать функцию для вывода чисел от 0 до 10, чтобы результат выглядел так:
//0 – ноль.
//1 – нечетное число.
//2 – четное число.
//3 – нечетное число.
//…
//10 – четное число.

function getNumbers(){
    $counter = 0;
    do{
        switch($counter){
            case 0:
                echo $counter . " - ноль." . PHP_EOL;
                break;
            case $counter%2 === 0:
                echo $counter . " - четное число." . PHP_EOL;
                break;
            default:
                echo $counter . " - нечетное число." . PHP_EOL;
        }

        $counter++;
    } while($counter <= 10);
}

getNumbers();