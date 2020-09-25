<?php
    class Auth extends Middleware {
        
        // * Imnplementation from base check function which is triggered on construct
        public function check(){
            $this->startSession();
            print_r($_SESSION);
            if(!isset($_SESSION['user'])){
                header('Location: /MVC/login');
            }
        }
    }