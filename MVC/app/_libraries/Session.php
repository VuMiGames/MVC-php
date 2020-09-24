<?php
    class Session {
        public function __construct(){

        }

        public function startSession(){
            if(session_status() == PHP_SESSION_NONE){
                session_start();
            }
        }
    }
?>