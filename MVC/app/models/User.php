<?php
    class User {

        public $db_properties;

        public function __construct($db_props = ['username', 'email', 'password']) {
            $this->db_properties = $db_props;
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