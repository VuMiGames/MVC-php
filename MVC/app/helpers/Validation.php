<?php
    class Validation{

        public $validators = [
            'auth:username' => '/^[\w]{4,11}$/',
            'auth:password' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[!@#$%^&*()])[a-zA-Z!@#$%^&*()\d]{5,}$/',
            'auth:email' => '/^\w+@\w+\.\w+$/'
        ];

        public $error_msgs = [
            'auth:username' => 'Username must be 4-11 characters length',
            'auth:password' => 'Password must contain 1 special, lower and uppercase character and min 5 overall',
            'auth:email' => 'Email has invalid format'
        ];

        public function __construct(){

        }

        public function validate($type, $data){
            if(!preg_match($this->validators[$type], $data)){
                return $this->error_msgs[$type];
            }
            return 'OK';
        }

        public function validateRegex($text, $regex){
            return preg_match($regex, $text);
        }

        public function validateGroup($typeArray, $dataArray){
            if(!is_array($typeArray) || !is_array($dataArray)){
                die('Validation group must be an array');
            }
            if(count($typeArray) < count($dataArray) || count($dataArray) < count($typeArray)){
                die('Type and data array must be the same length');
            }
            $errorsArr = [];
            for($i = 0; $i < count($typeArray); $i++){
                $type = $typeArray[$i];
                $data = $dataArray[$i];
                if(!preg_match($this->validators[$type], $data)){
                    array_push($errorsArr, $this->error_msgs[$type]);
                }
            }
            return $errorsArr;
        }
    }