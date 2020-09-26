<?php
    class PasswordRecovery extends Model {

        public $email;
        public $token;
        public $used;
        public $activated_ip;
        public $time_expiration;
        public $time_created;

        public function __construct(){
            $this->setIDfield("id");
        }

    }