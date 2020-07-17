<?php
    function power($val, $pow){
        if($pow < 0) return false;

        switch($pow){
            case 0:
                $result = 1;
                break;
            case 1:
                $result = $val;
                break;
            case 2:
                $result = $val * $val;
                break;
            default:
                $result = $val * power($val, $pow - 1);
        }

        return $result;
    }

    echo power(2, 10);