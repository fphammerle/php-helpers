<?php

namespace fphammerle\helpers;

class StringHelper
{
    /**
     * @return string
     */
    public static function prepend($prefix, $text)
    {
        if(is_array($text)) {
            $result = [];
            foreach($text as $key => $value) {
                $result[$key] = self::prepend($prefix, $value);
            }
            return $result;
        } else {
            return ($text === null) ? null : ($prefix . $text);
        }
    }

    /**
     * @return string
     */
    public static function append($text, $postfix)
    {
        if(is_array($text)) {
            $result = [];
            foreach($text as $key => $value) {
                $result[$key] = self::append($value, $postfix);
            }
            return $result;
        } else {
            return ($text === null) ? null : ($text . $postfix);
        }
    }

    /**
     * @return string
     */
    public static function embed($prefix, $text, $postfix)
    {
        return self::prepend($prefix, self::append($text, $postfix));
    }

    /**
     * @return string
     */
    public static function embrace($brace, $text)
    {
        return self::embed($brace, $text, $brace);
    }

    /**
     * @return string|null
     */
    public static function implode($glue, array $pieces)
    {
        $pieces = array_filter($pieces);
        if(sizeof($pieces) == 0) {
            return null;
        } else {
            return implode($glue, $pieces);
        }
    }

    /**
     * @throws InvalidArgumentException empty needle
     * @param array $needles
     * @param string $haystack
     * @return bool
     */
    public static function containsAny(array $needles, $haystack)
    {
        foreach($needles as $needle) {
            if(empty($needle)) {
                throw new \InvalidArgumentException('empty needle');
            } elseif(strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}
