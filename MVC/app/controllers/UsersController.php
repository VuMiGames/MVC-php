<?php
    //Passing variables to view
    /*
        * Access data by typing: data[arg] => data['title']
    */

    class UsersController extends Controller {

        // * Assign the repositories that this controller is using
        public function __construct(){
            $this->loadRepository();
            $this->getRepository()->useRepo('users');
            $this->getRepository()->useRepo('posts');
        }

        // * The main function for view
        public function index(){
            require_once '../models/User.php';

            $newUser = new User();
            $newUser->username = "Michu123";
            $newUser->email = "email@gmail.com";
            $newUser->password = "password-test";
            $this->getRepository()->save('users', $newUser);

            $user = $this->getRepository()->getByTag('users', 1, 'user_id');

            $data = 
            [
                'title' => 'Pages',
                'data' => $user->username
            ];
            
            $this->view('users/index', $data);
        }
    }