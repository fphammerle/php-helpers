<?php

namespace fphammerle\helpers;

class ArrayHelper
{
    /**
     * @return array
     */
    public static function flatten(array $arr)
    {
        return iterator_to_array(
            new \RecursiveIteratorIterator(new \RecursiveArrayIterator($arr)),
            false
            );
    }

    /**
     * @param mixed $source_array
     * @param \Closure $callback
     * @return mixed
     */
    public static function map($source, \Closure $callback)
    {
        $callback_reflection = new \ReflectionFunction($callback);
        if($callback_reflection->getNumberOfRequiredParameters() == 1) {
            $mapper = function($k, $v) use ($callback) {
                return $callback($v);
                };
        } else {
            $mapper = $callback;
        }
        if(is_array($source)) {
            return self::multimap($source, function($k, $v) use ($mapper) {
                return [$k => $mapper($k, $v)];
                });
        } else {
            return $mapper(null, $source);
        }
    }

    /**
     * @param mixed $source_array
     * @param \Closure $callback
     * @return mixed
     */
    public static function mapIfSet($source, \Closure $callback)
    {
        if($source === null) {
            return null;
        } else {
            $callback_reflection = new \ReflectionFunction($callback);
            if($callback_reflection->getNumberOfRequiredParameters() == 1) {
                $mapper = function($k, $v) use ($callback) {
                    return is_null($v) ? null : $callback($v);
                    };
            } else {
                $mapper = function($k, $v) use ($callback) {
                    return is_null($v) ? null : $callback($k, $v);
                    };
            }
            return self::map($source, $mapper);
        }
    }

    /**
     * @param array $source_array
     * @param \Closure $callback
     * @return array
     */
   public static function multimap(array $source_array, \Closure $callback)
   {
       $mapped_array = [];
       foreach($source_array as $old_key => $old_value) {
           $pairs = $callback($old_key, $old_value);
           if($pairs === null) {
               // skip
           } elseif(is_array($pairs)) {
               foreach($pairs as $new_key => $new_pair) {
                   $mapped_array[$new_key] = $new_pair;
               }
           } else {
               throw new \UnexpectedValueException(
                    sprintf('expected array, %s given', gettype($pairs))
                    );
           }
       }
       return $mapped_array;
   }
}
