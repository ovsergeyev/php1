<?php
    $str = null;
    $timestamp = time();

    $hour = date('H', $timestamp);
    $minutes = date('i', $timestamp);

    if($hour%100 > 4 && $hour%100 < 20) {
        $str = $hour . " часов";
    } elseif($hour%10 == 1){
        $str = $hour . " час";
    } elseif($hour%10 > 1 && $hour%10 < 5){
        $str = $hour . " часа";
    }

    $str = $str . " " . $minutes;

    if($minutes%100 > 10 && $minutes < 15){
        $str = $str . " минут";
    } elseif($minutes%10 == 1){
        $str = $str . " минута";
    } elseif($minutes%10 > 1 && $minutes%10 < 5){
        $str = $str . " минуты";
    } else {
        $str = $str . " минут";
    }


    echo $str;

