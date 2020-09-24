<?php
    class AuthController extends Controller{
        public function __construct(){

        }

        // * PAGES FOR LOGIN AND REGISTER
        public function loginPage(){
            $this->view('auth/login');
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                unset($_SESSION['user']);
            }
        }

        public function registerPage(){

        }

        // * POST HANDLE
        public function login(){
            $name = $_POST['user'];
            $password = $_POST['password'];
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                $_SESSION['user'] = $name;
                header('Location: /MVC/users');
            }
        }

        public function register(){

        }
    }