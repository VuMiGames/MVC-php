<?php
    /*
    * App Core Class
    * Creates URL & loads core controller
    * URL FORMAT - /controller/method/params
    */

    class Core{
        protected $currentController = 'PagesController';
        protected $currentMethod = 'index';
        protected $params_arr = [];

        public function __construct(){
            //print_r($this->getUrl());

            $url = $this->getUrl();

            if(isset($url[0])){
                // Look in controllers for first value
                // Current path is in index.php (main one)
                if(file_exists('../app/controllers/' . ucwords($url[0]) . 'Controller.php')){
                    // If exists, set as controller
                    $this->currentController = ucwords($url[0] . 'Controller');
                    // Unset 0 Index
                    unset($url[0]);
                }

                // Require the controller
                require_once '../app/controllers/' . $this->currentController . '.php';

                // Instantiate controller class
                $this->currentController = new $this->currentController;

                // Check for second part of url
                if(isset($url[1])){
                    // Check to see if methods exists in controller
                    if(method_exists($this->currentController, $url[1])){
                        $this->currentMethod = $url[1];
                        //Unset 1 Index
                        unset($url[1]);
                    }
                }
                
                // Get params
                $this->params_arr = $url ? array_values($url) : [];

                // Call a callback with array of params
                call_user_func_array([$this->currentController, $this->currentMethod], $this->params_arr);
            }else{
                //Home page index.php
            }
        }

        public function getUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
            
        }
    }
    
?>