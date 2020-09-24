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
            //* CHECK FOR username and password validation
            $errors = [];
            require_once '../app/helpers/Validation.php';
            $valid = new Validation();
            $name_mess = $valid->validate('auth:username', $name);
            $pass_mess = $valid->validate('auth:password', $password);
            if($name_mess != 'OK'){
                array_push($errors, $name_mess);
            }
            if($pass_mess != 'OK'){
                array_push($errors, $pass_mess);
            }

            if(count($errors) > 0){
                $this->view('auth/login', ['errors'=>$errors]);
                exit;
            }

            /*
                * CHECK DB for username and password
            */
            /*if(session_status() == PHP_SESSION_NONE){
                session_start();
                $_SESSION['user'] = $name;
                //header('Location: /MVC/login');
                $this->view('auth/login', ['errors' => $errors]);
            }*/
        }

        public function register(){

        }
    }