<?php
    class Repository {
        protected $db;
        protected $repos;

        public function __construct($repos=[]){
            $this->db = new Database();
            $this->repos = $repos;
        }

        public function useRepo($repo){
            array_push($this->repos, $repo);
        }

        public function deleteRepo($repo){
            if(!in_array($repo, $this->repos)){
                die('Repository not exist');
            }
            for($i = 0; $i < count($this->repos); $i++){
                if($this->repos[$i] == $repo){
                    unset($this->repos[$i]);
                    die();
                }
            }
        }

        protected function getAll($repo){
            if(!in_array($repo, $this->repos)){
                die('Repository not exist');
            }
            $sql = "SELECT * FROM $repo";
            $this->db->query($sql);
            return $this->db->resultSet();
            //echo "<pre>"; print_r($this->db->resultSet()); echo "</pre>";
        }

        protected function getByTag($repo, $valueSearch, $tag = "id"){
            if(!in_array($repo, $this->repos)){
                die('Repository not exist');
            }
            if(strpos(trim($repo), ' ') !== false || strpos(trim($valueSearch), ' ') !== false || strpos(trim($tag), ' ') !== false)
            {
                die('Access denied');
            }

            $sql = "SELECT * FROM users WHERE $tag = :valueSearch";
            $this->db->query($sql);
            $this->db->bind(":valueSearch", $valueSearch);
            return $this->db->single();
        }

        // If you need test data you can put more in $count, by using
        // * save('repo', obj, 100)
        // * 10 is safe limit, it's still WIP
        protected function save($repo, $object, $count = 1){

            if(!in_array($repo, $this->repos)){
                die('Repository not exist');
            }
            if(!is_object($object)){
                die('You need to pass object to save');
            }

            // * Get properties from Model Class
            $reflect = new ReflectionClass($object);
            $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

            $params_sql = "";
            $values_sql = "";
            foreach($props as $prop){
                $prop = $prop->getName();
                $params_sql = $params_sql . '`' . $prop . '`,';
                $values_sql = $values_sql . '\'' . $object->$prop . '\',';
            }
            $params_sql = substr($params_sql, 0, -1);
            $values_sql = substr($values_sql, 0, -1);

            $sql = "INSERT INTO $repo ($params_sql) VALUES ($values_sql)";
            for($i = 0; $i<$count; $i++){
                $this->db->query($sql);
                $this->db->execute();
            }
        }
        // * Delete any model from DB
        protected function delete($repo, $object, $id){
            if(!in_array($repo, $this->repos)){
                die('Repository not exist');
            }
            if(!is_object($object)){
                die('You need to pass object to save');
            }

            // * Get properties from Model Class
            $reflect = new ReflectionClass($object);
            $props = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);

            $params_sql = "";
            $values_sql = "";
            foreach($props as $prop){
                $prop = $prop->getName();
                $params_sql = $params_sql . '`' . $prop . '`,';
                $values_sql = $values_sql . '\'' . $object->$prop . '\',';
            }
            $params_sql = substr($params_sql, 0, -1);
            $values_sql = substr($values_sql, 0, -1);

            $sql = "INSERT INTO $repo ($params_sql) VALUES ($values_sql)";
            for($i = 0; $i<$count; $i++){
                $this->db->query($sql);
                $this->db->execute();
            }
        }
    }
?>