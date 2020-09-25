<?php
    class AuthController extends Controller{
        protected $repo;

        protected $shouldWait = 1;
        protected $wait_secs = 1;

        public function __construct(){
            $this->loadRepository();
            $this->repo = $this->getRepository();
            $this->repo->useRepo('users');
        }

        // * HANDLE GET request for login Page
        public function loginPage(){
            $this->view('auth/login');
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                unset($_SESSION['user']);
            }
        }

        // * HANDLE GET request for register Page
        public function registerPage(){

        }

        // * HANDLE POST request for login Page
        public function login(){
            $name = $_POST['user'];
            $password = $_POST['password'];
            // ? CHECK FOR username and password validation
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

            // ? Check if any regex errors [Check Validation class in helpers folder]
            if(count($errors) > 0){
                $this->view('auth/login', ['errors' => $errors]);
                exit;
            }

            // ? CHECK DB for username and password
            $users = $this->repo->getAll('users');
            require_once '../app/helpers/ArrayUtils.php';
            $userFound = ArrayUtils::objArraySearch($users, 'username', $name);
            
            // ? Check if users exist
            if($userFound === null){
                $this->view('auth/login', ['errors' => 'User does not exist or password is not the same']);
                exit;
            }

            // ? If user exist, then check the password
            if(md5($password) != $userFound->password){
                $this->view('auth/login', ['errors'=>'User does not exist or password is not the same']);
                exit;
            }

            // * Header the user to panel page
            $this->view('auth/login', ['logged_in'=>'Authentication success, logging in...']);
            // TODO Just for safety now, will be removed after adding session check
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                $_SESSION['user'] = $name;
            }
            $this->headerAfterLogin();
        }

        function headerAfterLogin(){
            // ! if $should_wait == 1, then it will wait $wait_secs, showing OK message and then changing window location
            if($this->shouldWait == 1){
                echo '<script>setTimeout(function(){window.location.href="/MVC/users"}, ' . ($this->wait_secs * 1000) . ');</script>';
            }else{
                header('Location: /MVC/users');
            }
        }

        // * HANDLE POST request for register Page
        public function register(){

        }
    }