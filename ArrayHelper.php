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
       if(is_array($source)) {
           return array_map($callback, $source);
       } else {
           return $callback($source);
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
        } elseif(is_array($source)) {
            return array_map(
                function($v) use ($callback) {
                    return $v === null ? null : $callback($v);
                    },
                $source
                );
       } else {
           return $callback($source);
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
               // skipp
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
