<?php
    class DocsController extends Controller {
        public function __contruct(){


        }

        public function index(){
            $this->view('docs/index', ['title' => 'Docs']);
        }
    }
?>