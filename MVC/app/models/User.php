<?php
    class User extends Model {

        public $username;
        public $email;
        public $password;
        public $status;

        public function __construct(){
            $this->setIDfield("user_id");
        }

    }