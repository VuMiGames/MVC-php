<?php
declare(strict_types=1);
    class Router {
        protected $routes = array();

        public function __construct(){

        }

        public function getRoutes(){
            return $this->routes;
        }

        public function getRoute($routeExist, $req_type): ?object{
            foreach($this->routes as $route){
                if($routeExist == $route->getView() && $req_type == $route->getRequestType()){
                    return $route;
                }
            }
            return null;
        }

        public function addRoute($route){
            if($this->routeExists($route->getView(), $route->getRequestType())){
                return false;
            }
            array_push($this->routes, $route);
            return true;
        }

        public function routeExists($routeExist, $req_type){
            return $this->getRoute($routeExist, $req_type) === null ? false : true;
        }
    }
?>