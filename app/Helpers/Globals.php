<?php

if (!function_exists('getProperty')) {
    function getProperty($obj, $property, $failedReturn = null) 
    {
        if (property_exists($obj, $property)) {
            return $obj->$property;
        }
        return $failedReturn;
    }
}

if (!function_exists('getPercent')) {
    function getPercent(int $value) 
    {
        return round($value * 100, 1);
    }
}

if (!function_exists('displayMoney')) {
    function displayMoney(int $value) 
    {
        return number_format($value / 100, 2);
    }
}

