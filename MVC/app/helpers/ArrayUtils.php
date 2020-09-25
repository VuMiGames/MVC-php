<?php
    class ArrayUtils{
        public function __construct(){


        }

        public static function objArraySearch($array, $index, $value)
        {
            foreach($array as $arrayInf) {
                if($arrayInf->{$index} == $value) {
                    return $arrayInf;
                }
            }
            return null;
        }
    }