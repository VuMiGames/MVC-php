<?php
    class Route {
        protected $middlewares = array();
        protected $view = '';
        protected $controllerMethod = NULL;
        protected $request_type = 'GET';

        public function __construct($request, $view, $controller, $midd = array()){
            $this->request_type = $request;
            $this->view = $view;
            $this->controllerMethod = $controller;
            $this->middlewares = $midd;
        }

        public function checkRequestType(){
            if ($_SERVER['REQUEST_METHOD'] !== $this->request_type) {
                // The request is using the correct method
                //TODO 
                /*
                    * add better location referrer
                */
                //header('Location: ' . $_SERVER['HTTP_REFERER']);
                die('Access denied - request mode not allowed');
            }
        }

        public function getRequestType(){
            return $this->request_type;
        }

        public function triggerController(){
            //Controller array -> Controller@method
            $cArr = explode('@', $this->controllerMethod);
            $controller = $cArr[0];
            $method = $cArr[1];
            try{
                if(file_exists('../app/controllers/' . ucwords($controller) . '.php')){
                    // If exists,
                    // Require the controller
                    require_once '../app/controllers/' . ucwords($controller) . '.php';
                    // Instantiate controller class
                    $controller = new $controller;
                    if(method_exists($controller, $method)){
                        $controller->$method();
                    }else{
                        throw new Exception('Controller method: "' . $method . '", does not exist');
                    }
                }else{
                    throw new Exception('Controller "' . $controller . '" does not exist');
                }
            }
            catch (Exception $e){
                echo $e->getMessage();
                echo '<br>';
            }
        }

        public function triggerMiddlewares(){
            try{
                foreach($this->middlewares as $midd){
                    if(file_exists('../app/middlewares/' . ucwords($midd) . '.php')){
                        // If exists,
                        // Require the middleware
                        require_once '../app/middlewares/' . ucwords($midd) . '.php';
                        // Instantiate middleware class
                        $midd = new $midd;
                    }else{
                        throw new Exception('Middleware "' . $midd . '" does not exist');
                    }
                }
            }
            catch (Exception $e){
                echo $e->getMessage();
                echo '<br>';
                return false;
            }
            return true;
        }

        public function getMidds(){
            return $this->middlewares;
        }

        public function getView(){
            return $this->view;
        }

        public function showView(){
            // Require the view
            require_once '../app/views/' . $this->view . '.php';
        }
    }