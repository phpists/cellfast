<?php

namespace noIT\soap\components;

use yii\base\Component;
use yii\db\Expression;

abstract class SoapDataProcessor extends Component
{
    public static function str2Boolean($value, $directSql = false)
    {
        if ($value === "false") {return ($directSql) ? $value : false;}
        elseif ($value === "true") {return ($directSql) ? $value : true;}
        return null;
    }

    public static function str2Guid($value, $directSql = false)
    {
        if (preg_match(
            '/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $value)) {
            if ($directSql) {
                return "UuidToBin('{$value}')";
            } else {
                return new Expression("UuidToBin('{$value}')");
            }
        }
        return null;
    }

    public static function str2Timestamp($value, $directSql = false)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}$/', $value)) {
            return str_replace("T", " ", $value);
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }
        return null;
    }

    public static function str2Date($value, $directSql = false)
    {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }
        return null;
    }
}