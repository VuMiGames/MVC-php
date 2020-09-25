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
    }