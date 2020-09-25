<?php
    class AuthController extends Controller{

        protected $wait_after_login = 1;
        protected $wait_after_register = 1;

        protected $wait_after_login_ms = 1500;
        protected $wait_after_register_ms = 1500;

        protected $must_login_after_register = 1;

        protected $page_after_login = "/MVC/users";
        // ! This will work if $must_login_after_register == 0
        protected $page_after_register = "/MVC/users";

        public function __construct(){
            $this->loadRepository();
            $this->repo->useRepo('users');
        }

        // * HANDLE GET request for login Page
        public function loginPage(){
            $this->view('auth/login');
        }

        // * HANDLE GET request for logout page
        public function logout(){
            if(session_status() != PHP_SESSION_NONE){
                session_start();
                if(isset($_SESSION['user'])){
                    unset($_SESSION['user']);
                    unset($_SESSION['email']);
                }
                session_destroy();
            }else{
                if(isset($_SESSION['user'])){
                    unset($_SESSION['user']);
                    unset($_SESSION['email']);
                    session_destroy();
                }
            }
            $this->changeLocation('/MVC/login');
        }

        // * HANDLE GET request for register Page
        public function registerPage(){
            $this->view('auth/register');
        }

        function changeLocation($location, $wait = 0, $wait_ms = 0){
            // ! if $should_wait == 1, then it will wait $wait_ms miliseconds, showing OK message and then changing window location
            if($wait == 1){
                echo '<script>setTimeout(function(){window.location.href="' . $location . '"}, ' . $wait_ms . ');</script>';
            }else{
                header('Location: ' . $location);
            }
        }

        // * HANDLE POST request for login Page
        public function login(){
            $name = $_POST['user'];
            $password = $_POST['password'];

            // ? VALIDATE and return errors if any
            $errors = $this->validator->validateGroup(['auth:username', 'auth:password'], [$name, $password]);
            if(count($errors) > 0){
                $this->view('auth/login', ['errors' => $errors]);
                exit;
            }

            // ? CHECK DB for username and password
            $users = $this->repo->getAll('users');
            require_once '../app/helpers/ArrayUtils.php';
            $userFound = ArrayUtils::objArraySearch($users, 'username', $name);
            
            // ? Check if users exist, if yes, also check its password
            if($userFound === null || md5($password) != $userFound->password){
                $this->view('auth/login', ['errors' => 'User does not exist or password is not the same']);
                exit;
            }

            // * Header the user to panel page
            $this->view('auth/login', ['logged_in'=>'Authentication success, logging in...']);
            // TODO Just for safety now, will be removed after adding session check
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                $_SESSION['user'] = $name;
                $_SESSION['email'] = $userFound->email;
            }
            $this->changeLocation($this->page_after_login, $this->wait_after_login, $this->wait_after_login_ms);
        }

        // * HANDLE POST request for register Page
        public function register(){
            $email = $_POST['email'];
            $name = $_POST['user'];
            $password = $_POST['password'];

            // ? VALIDATE and return errors if any
            $errors = $this->validator->validateGroup(['auth:email', 'auth:username', 'auth:password'], [$email, $name, $password]);
            if(count($errors) > 0){
                $this->view('auth/register', ['errors' => $errors]);
                exit;
            }

            // ? Check if username exist
            $users = $this->repo->getAll('users');
            require_once '../app/helpers/ArrayUtils.php';
            $userFound = ArrayUtils::objArraySearch($users, 'username', $name);
            
            if($userFound !== null){
                $this->view('auth/register', ['errors' => 'This username already exist']);
                exit;
            }

            // ? Check if this email exist
            $userFound = ArrayUtils::objArraySearch($users, 'email', $email);
            if($userFound !== null){
                $this->view('auth/register', ['errors' => 'This email already exist']);
                exit;
            }

            // * Add user to the DB
            require_once '../app/models/User.php';
            $newUser = new User();
            $newUser->username = $name;
            $newUser->password = md5($password);
            $newUser->email = $email;
            $user_id = $this->getRepository()->save('users', $newUser);

            // ! if must_login_after_register == 1, then user must login after registration, if not, he will be in panel
            $this->view('auth/register', ['registered'=>'Registration success, logging in...']);
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                $_SESSION['user'] = $name;
                $_SESSION['email'] = $email;
                $_SESSION['user_id'] = $user_id;
            }
            $this->changeLocation(($this->must_login_after_register == 1 ? '/MVC/login' : $this->page_after_register), $this->wait_after_register, $this->wait_after_register_ms);
        }
    }