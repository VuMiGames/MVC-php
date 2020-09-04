<?php
    //Passing variables to view
    /*
        * Access data by typing: data[arg] => data['title']
    */

    class UsersController extends Controller {
        public function __construct(){
            $this->userModel = $this->model('User');
        }

        public function index(){
            $users = $this->userModel->getPosts();

            $data = 
            [
                'title' => 'Welcome',
                'users' => $users
            ];
            
            $this->view('users/index', $data);
        }
    }