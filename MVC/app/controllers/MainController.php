<?php
    //Passing variables to view
    /*
        * Access data by typing: data[arg] => data['title']
    */

    class MainController extends Controller {

        public $dataX = "";

        // * Assign the repositories that this controller is using
        public function __construct(){
            
        }

        // * The main function for view
        public function index(){
            $this->dataX = "TOTOT";
            $this->view('index', ['title' => 'Main']);
        }
    }