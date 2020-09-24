<?php
    class Router {
        protected $routes = array();

        public function __construct(){

        }

        public function getRoutes(){
            return $this->routes;
        }

        public function addRoute($route){
            if($this->routeExists($route->getView())){
                return false;
            }else{
                array_push($this->routes, $route);
                return true;
            }
        }

        public function routeExists($routeExist){
            foreach($this->routes as $route){
                $route = $route->getView();
                if($routeExist == $route){
                    return true;
                }
            }
            return false;
        }

        public function getRoute($routeExist){
            foreach($this->routes as $route){
                if($routeExist == $route->getView()){
                    return $route;
                }
            }
            return NULL;
        }
    }
?>