<?php

namespace Spot2Generator\core\helpers;


class StringHelpers
{
    public static function toUpCase($string, $del = '_')
    {

        $str = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if ($char == $del) {
                $string[$i + 1] = ucfirst($string[$i + 1]);
                continue;
            }

            $str .= $char;
        }

        return ucfirst($str);
    }
}