<?php
    /*
    * Base Controller
    * Loads the repository and views
    */
    class Controller extends Repository {
        
        protected $repo;

        // Set repository value
        public function loadRepository(){
            $this->repo = new Repository();
        }

        // Get a reference to repository
        public function getRepository(){
            return $this->repo;
        }

        // Load view
        public function view($view, $data = [], $midw = []){
            if(count($midw) > 0){
                foreach($midw as $mid){
                    // Check for middleware file
                    if(file_exists('../app/middlewares/' . $mid . '.php')){
                        require_once '../app/middlewares/' . $mid . '.php';
                        $middle = new $mid();
                    }else{
                        die('Middleware does not exist');
                    }
                }
            }

            // Check for view file
            if(file_exists('../app/views/' . $view . '.php')){
                require_once '../app/views/' . $view . '.php';
                return $this;
            }else{
                die('View does not exist');
            }
        }

        public function addMiddleware($midw){
            if(file_exists('../app/middlewares/' . $midw . '.php')){
                require_once '../app/middlewares/' . $midw . '.php';
                $middle = new $midw();
                return $this;
            }else{
                die('Middleware does not exist');
            }
        }
    }
?>