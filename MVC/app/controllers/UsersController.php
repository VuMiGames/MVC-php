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
            require_once '../app/models/User.php';

            $newUser = new User();
            $newUser->username = "Michu123";
            $newUser->email = "email@gmail.com";
            $newUser->password = "password-test";
            $newUser->status = "User";
            $id_insert = $this->getRepository()->save('users', $newUser);
            $newUser->setID($id_insert[0]);

            $users = $this->getRepository()->getAll('users');

            $data =
            [
                'title' => 'Pages',
                'data' => $users
            ];
            
            $this->view('users/index', $data);
        }
    }