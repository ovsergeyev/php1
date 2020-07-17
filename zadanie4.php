<?php

function sum($arg1, $arg2){
    $result = $arg1 + $arg2;
    return $result;
}

function diff($arg1, $arg2){
    $result = $arg1 - $arg2;
    return $result;
}

function mult($arg1, $arg2){
    $result = $arg1 * $arg2;
    return $result;
}

function division($arg1, $arg2){
    $result = $arg1 / $arg2;
    return $result;
}

function mathOperation($arg1, $arg2, $operation){
    if($operation == "sum" || $operation == "diff" || $operation == "mult" || $operation == "division"){
        return $operation($arg1, $arg2);
    } else {
        return false;
    }
}

echo mathOperation(10, 12, "mult");