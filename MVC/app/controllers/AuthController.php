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
            $this->repo->useRepo('pass_recoveries');
        }

        // * HANDLE GET request for login Page
        public function loginPage(){
            $this->view('auth/login');
        }

        // * HANDLE GET request for logout page
        public function logout(){
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                session_unset();
                session_destroy();
            }else{
                session_unset();
                session_destroy();
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
            $user_id = $this->getRepository()->create('users', $newUser);

            // ! if must_login_after_register == 1, then user must login after registration, if not, he will be in panel
            $this->view('auth/register', ['registered'=>'Registration success, logging in...']);
            if(session_status() == PHP_SESSION_NONE){
                session_start();
                $_SESSION['user'] = $name;
                $_SESSION['email'] = $email;
                //$_SESSION['user_id'] = $user_id;
            }
            $this->changeLocation(($this->must_login_after_register == 1 ? '/MVC/login' : $this->page_after_register), $this->wait_after_register, $this->wait_after_register_ms);
        }

        // * HANDLE GET request for forgot Page
        public function forgotPage(){
            $this->view('auth/forgot');
        }

        // * HANDLE POST request for forgot Page
        public function forgot(){
            $email = $_POST['email'];

            // ? VALIDATE and return errors if any
            $errors = $this->validator->validateGroup(['auth:email'], [$email]);
            if(count($errors) > 0){
                $this->view('auth/forgot', ['errors' => $errors]);
                exit;
            }

            // ? Check if username exist
            $users = $this->repo->getAll('users');
            require_once '../app/helpers/ArrayUtils.php';
            $userFound = ArrayUtils::objArraySearch($users, 'email', $email);
            
            // ! For security reason, you should rather say that if user exist then email was sent, so user can't check if email exist in DB
            if($userFound === null){
                $this->view('auth/forgot', ['logged_in' => 'Email was sent. If account exist, the recovery email should arrive within minutes']);
                exit;
            }
            // ! 1 as it's recovery email, please check sendEmail function below
            $this->sendRecoveryEmail('vumigames@gmail.com');
        }

        public function recoveryPage(){
            if(!isset($_GET['token'])){
                // ! Show 404, or need authorization / token again
                exit;
            }
            $token = $_GET['token'];
            $email_recovery = $this->getRepository()->getByTag('pass_recoveries', $token, 'token');
            if($email_recovery->used == 0){
                $this->view('auth/recovery', ['token' => $token]);
            }else{
                // * Token was already used, back to forgot page
                $this->view('auth/forgot', ['errors' => 'Token was already used, if you still need to recover, click button again.<br>If it was\'nt you that activated the token, please contact administrator.']);
            }
        }

        public function recovery(){
            $emails = $this->getRepository()->getAll('pass_recoveries');
            $users = $this->getRepository()->getAll('users');

            require_once '../app/helpers/ArrayUtils.php';
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            // * Find recovery email and update 'used' attribute value to 1
            $recoveryEmail = ArrayUtils::objArraySearch($emails, 'token', $token, 'PasswordRecovery');
            // * Find user by email
            $user = ArrayUtils::objArraySearch($users, 'email', $recoveryEmail->email, 'User');
            $old_pwd = $user->password;

            // ? VALIDATE and return errors if any
            $errors = $this->validator->validateGroup(['auth:password'], [$newPassword]);
            if($old_pwd == md5($newPassword)){
                array_push($errors, 'You must use different password than the recent one');
            }
            if(count($errors) > 0){
                $this->view('auth/recovery', ['errors' => $errors, 'token' => $token]);
                exit;
            }

            // * SET 'used' attribute value to 1, so user cannot use it again
            $recoveryEmail->used = 1;
            $this->getRepository()->updateObj('pass_recoveries', $recoveryEmail, ['used'], [1]);

            // * Update user in DB
            $user->password = md5($newPassword);
            $this->getRepository()->updateObj('users', $user, ['password'], [md5($newPassword)]);

            $this->view('auth/recovery', ['success' => 'Password changed, you can now login']);
            $this->changeLocation('/MVC/login', 1, 1500);
        }

        public function sendRecoveryEmail($email){
            // * If it's recovery email, then make object as well
            require_once '../app/models/PasswordRecovery.php';
            $email_obj = new PasswordRecovery();
            $email_obj->email = $email;
            //Generate a random string.
            $token = openssl_random_pseudo_bytes(32);
            
            //Convert the binary data into hexadecimal representation.
            $token = bin2hex($token);
            $email_obj->token = $token;
            $email_obj->used = 0;
            $email_obj->activated_ip = "";
            // ! Recovery email will be active for 24*2 = 48h
            $email_obj->time_expiration = date('Y-m-d H:i:s', time() + (2*24*60*60));
            $email_obj->time_created = date('Y-m-d H:i:s', time());
            $this->getRepository()->create('pass_recoveries', $email_obj);
        
            $subject = "Password recovery for MVC framework";
            
            $message = "<b>Use this link to recover password</b><br/><a href=" . 'localhost/MVC/recovery?token=' . $token . '>Click Me</a>';
            $message .= "<h1>Thanks for trust in us.</h1>";

            $this->sendEmail($email, $subject, $message, 1);
        }

        public function sendEmail($email, $subject, $message, $recoveryEmail = 0){
            $to = $email;
            
            $header = "From:mvc@michu-github.com \r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html\r\n";
            
            // * Send email
            $retval = mail ($to,$subject,$message,$header);

            if( $retval == true ) {
                $this->view('auth/forgot', ['success' => 'Email sent, please check your inbox']);
            }else {
                $this->view('auth/forgot', ['errors' => 'Email could not be sent, email invalid, please contact Admin']);
            }
        }
    }