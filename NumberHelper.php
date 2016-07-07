<?php

namespace fphammerle\helpers;

class NumberHelper
{
    static $roman_symbols = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
        ];

    /**
     * @return string|null
     */
    public static function formatRoman($number)
    {
        if($number === null) {
            return null;
        }

        if(!is_int($number)) {
            throw new \InvalidArgumentException('expected integer');
        }

        $roman_number = '';
        while($number > 0) {
            foreach(self::$roman_symbols as $roman_digit => $roman_digit_value) {
                if($number >= $roman_digit_value) {
                    $roman_number .= $roman_digit;
                    $number -= $roman_digit_value;
                    break;
                }
            }
        }

        return $roman_number;
    }
}

