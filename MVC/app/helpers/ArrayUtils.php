<?php
    class ArrayUtils{
        public function __construct(){


        }

        public static function objArraySearch($array, $index, $value, $class_to_cast = null)
        {
            foreach($array as $arrayInf) {
                if($arrayInf->{$index} == $value) {
                    if($class_to_cast == null){
                        return $arrayInf;
                    }else{
                        if(!file_exists('../app/models/'.$class_to_cast.'.php')){
                            die('This model does not exist');
                        }
                        require_once '../app/models/'.$class_to_cast.'.php';
                        $obj = new $class_to_cast();
                        // * Get properties from class_to_cast Class
                        $reflect = new ReflectionClass($obj);
                        $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

                        foreach($props as $prop){
                            $prop = $prop->getName();
                            $obj->$prop = $arrayInf->$prop;
                        }
                        return $obj;
                    }
                }
            }
            return null;
        }
    }