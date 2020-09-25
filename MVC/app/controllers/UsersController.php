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
            $users = $this->getRepository()->getAll('users');

            $data =
            [
                'title' => 'Pages',
                'users' => $users
            ];
            
            $this->view('users/index', $data);
        }
    }