<?php
    //Passing variables to view
    /*
        * Access data by typing: data[arg] => data['title']
    */

    class PagesController extends Controller {

        // * Assign the model that this controller is using
        public function __construct(){
            $this->loadRepository();
            $this->getRepository()->addRepo('users');
            $this->getRepository()->addRepo('posts');
            $this->getRepository()->getAll('posts');
        }

        // * The main function for view
        public function index(){
            $data = 
            [
                'title' => 'Pages'
            ];
            
            $this->view('pages/index', $data);
        }

        public function about(){
            $data = 
            [
                'title' => 'About Ass'
            ];
            
            $this->view('pages/about', $data);
        }
    }