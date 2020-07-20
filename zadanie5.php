<?php

$str = "Строка содержит пробелы";

function replace_space($str){
    return str_replace(" ", "_", $str);
}

echo replace_space($str);