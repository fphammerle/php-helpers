<?php

namespace fphammerle\helpers;

class HtmlHelper
{
    public static function encode($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    private static function _renderTagAttributes(array $attributes = [])
    {
        return StringHelper::implode(' ', ArrayHelper::mapIfSet(
            $attributes,
            function($k, $v) {
                if($v === true) {
                    return sprintf('%s="%s"', $k, $k);
                } elseif($v === false) {
                    return null;
                } else {
                    return sprintf('%s="%s"', $k, self::encode($v));
                }
                }
            ));
    }

    public static function voidTag($tag_name, array $attributes = [])
    {
        if($tag_name === null) {
            return null;
        } elseif(!is_string($tag_name)) {
            throw new \TypeError(
                sprintf('expected string or null as tag name, %s given', gettype($tag_name))
                );
        } else {
            return sprintf(
                '<%s%s />',
                $tag_name,
                StringHelper::prepend(' ', self::_renderTagAttributes($attributes))
                );
        }
    }

    public static function startTag($tag_name, array $attributes = [])
    {
        if($tag_name === null) {
            return null;
        } elseif(!is_string($tag_name)) {
            throw new \TypeError(
                sprintf('expected string or null as tag name, %s given', gettype($tag_name))
                );
        } else {
            return sprintf(
                '<%s%s>',
                $tag_name,
                StringHelper::prepend(' ', self::_renderTagAttributes($attributes))
                );
        }
    }

    public static function endTag($name)
    {
        if($name === null) {
            return null;
        } elseif(!is_string($name)) {
            throw new \TypeError(
                sprintf('expected string or null as name, %s given', gettype($name))
                );
        } else {
            return '</' . $name . '>';
        }
    }
}
