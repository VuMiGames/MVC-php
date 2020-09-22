<?php
    class Middleware {
        public function __construct(){
            $this->check();
        }

        protected function check(){
            // * There you put checking method
        }

        protected function startSession(){
            if(session_status() == PHP_SESSION_NONE){
                session_start();
            }
        }
    }