<?php
    /*
    * Base Controller
    * Loads the repository and views
    */
    class Controller extends Repository {
        
        protected $repo;
        protected $validator;

        // Set repository value
        public function loadRepository(){
            $this->repo = new Repository();
            require_once '../app/helpers/Validation.php';
            $this->validator = new Validation();
        }

        // Get a reference to repository
        public function getRepository(){
            return $this->repo;
        }

        // Load view
        public function view($view, $data = []){
            // Check for view file
            if(file_exists('../app/views/' . $view . '.php')){
                require_once '../app/views/' . $view . '.php';
                return $this;
            }else{
                die('View does not exist');
            }
        }
    }
?>