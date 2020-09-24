<?php
    class Auth extends Middleware {
        
        // * Imnplementation from base check function which is triggered on construct
        public function check(){
            $this->startSession();
            if(!isset($_SESSION['user'])){
                print_r('User not authenticated');
                return false;
            }
            return true;
        }
    }