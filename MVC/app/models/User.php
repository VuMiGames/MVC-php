<?php
    class User extends Model {

        public $username;
        public $email;
        public $password;

        public function __construct(){
            $this->setIDfield("id");
        }

    }