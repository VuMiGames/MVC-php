<?php
    //Passing variables to view
    /*
        * Access data by typing: data[arg] => data['title']
    */

    class UsersController extends Controller {

        // * Assign the model that this controller is using
        public function __construct(){
            $this->loadRepository();
            $this->getRepository()->addRepo('users');
            $this->getRepository()->addRepo('posts');
            $this->getRepository()->getAll('users');
        }

        // * The main function for view
        public function index(){
            $data = 
            [
                'title' => 'Pages'
            ];
            
            $this->view('users/index', $data);
        }
    }