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
        protected $router;

        public function routes(){
            $this->router = new Router();
            $this->router->addRoute(new Route('GET', '/', 'MainController@index', ['Auth']));
            $this->router->addRoute(new Route('GET', 'users', 'UsersController@index', ['Auth']));
            // ! AUTH
            $this->router->addRoute(new Route('GET', 'login', 'AuthController@loginPage'));
            $this->router->addRoute(new Route('POST', 'login', 'AuthController@login'));
            $this->router->addRoute(new Route('GET', 'logout', 'AuthController@logout'));
            $this->router->addRoute(new Route('GET', 'register', 'AuthController@registerPage'));
            $this->router->addRoute(new Route('POST', 'register', 'AuthController@register'));
            $this->router->addRoute(new Route('GET', 'forgot', 'AuthController@forgotPage'));
            $this->router->addRoute(new Route('POST', 'forgot', 'AuthController@forgot'));
            $this->router->addRoute(new Route('GET', 'recovery', 'AuthController@recoveryPage'));
            $this->router->addRoute(new Route('POST', 'recovery', 'AuthController@recovery'));
        }

        public function __construct(){
            $this->routes();

            $url = $this->getUrl();
            $urlAbs = isset($_GET['url']) ? $_GET['url'] : '/';

            // Then it's home '/' url
            if($url === NULL){
                if(!$this->router->routeExists($urlAbs, $_SERVER['REQUEST_METHOD'])){
                    /*
                        * Route not existing, show 404 page
                    */
                    require_once '../app/views/404.php';
                    exit;
                }
                // Route to home view
                $urlAbs = '/';
            }

            if(!$this->router->routeExists($urlAbs, $_SERVER['REQUEST_METHOD'])){
                /*
                    * Route not existing, show 404 page
                */
                require_once '../app/views/404.php';
                exit;
            }

            if(substr($urlAbs, -1) == '/' && strlen($urlAbs) != 1){
                $urlAbs = substr($urlAbs, 0, -1);
            }

            $route = $this->router->getRoute($urlAbs, $_SERVER['REQUEST_METHOD']);
            // * Check for request type
            $route->checkRequestType();
            // Add error handle
            $route->triggerMiddlewares() ? null : die('');
            $route->triggerController();

            /*

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
                // Not existing page, do 404
            }*/
        }

        public function getUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }else{
                return NULL;
            }
        }
    }
    
?>