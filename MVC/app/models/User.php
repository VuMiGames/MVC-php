<?php
    class User {

        private $id;
        public $username;
        public $email;
        public $password;
        public $status;

        public function getID(){
            return $this->user_id;
        }

        public function setID($id){
            
        }

        /*public function __get($param){
            if(!property_exists(__CLASS__, $param)){
                die('Not existing');
            }
            return $this->$param;
        }

        public function __set($param, $value){
            if(!property_exists(__CLASS__, $param)){
                die('Not existing');
            }
            $this->$param = $value;
        }*/
    }