<?php

//Объявить массив, в котором в качестве ключей будут использоваться названия областей, а в качестве значений – массивы с названиями городов из соответствующей области. Вывести в цикле значения массива, чтобы результат был таким:
//Московская область:
//Москва, Зеленоград, Клин
//Ленинградская область:
//Санкт-Петербург, Всеволожск, Павловск, Кронштадт
//Рязанская область … (названия городов можно найти на maps.yandex.ru)

$regions = [
    'Московская область' => ['Москва', 'Зеленоград', 'Клин'],
    'Ленинградская область' => ['Санкт-Петербург', 'Всеволожск', 'Павловск', 'Кронштаде'],
    'Рязанская область' => ['Рязань', 'Касимов', 'Скопин', 'Сасово'],
];

foreach($regions as $key => $value){
    echo $key . ":" . PHP_EOL;
    echo implode($value, ", ") . PHP_EOL;
}