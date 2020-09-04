<?php
    //Passing variables to view
    /*
        * Access data by typing: data[arg] => data['title']
    */

    class PagesController extends Controller {

        // * Assign the model that this controller is using
        public function __construct(){
            
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