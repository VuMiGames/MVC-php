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

            $sql = "SELECT * FROM " . $repo . " WHERE $tag = :valueSearch";
            $this->db->query($sql);
            $this->db->bind(":valueSearch", $valueSearch);
            return $this->db->single();
        }

        // If you need test data you can put more in $count, by using
        // * save('repo', obj, 100)
        // * 10 is safe limit, it's still WIP
        protected function create($repo, $object, $count = 1){
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
            $ids = [];
            for($i = 0; $i<$count; $i++){
                $this->db->query($sql);
                $this->db->execute();
                $ids[$i] = $this->db->lastInsertID();
            }
            return $ids;
        }

        // * UPDATE object
        protected function updateObj($repo, $object, $indexes, $values){
            if(!in_array($repo, $this->repos)){
                die('Repository not exist');
            }
            if(!is_object($object)){
                die('You need to pass object to update');
            }

            if((count($indexes) != count($values)) || (count($indexes) == 0 || count($values) == 0)){
                die('Indexes count and values count must be the same and > 0!');
            }

            if($this->getByTag($repo, $object->getID(), $object->getIDfield()) != null){
                $this->create($repo, $object);
                exit;
            }

            $params_sql = "";
            $i = 0;
            foreach($indexes as $index){
                $params_sql .= ('`' . $index . '` = ');
                $val = is_int($values[$i]) ? ($values[$i] . ',') : ('"' . $values[$i] . '",');
                $params_sql .= $val;
                $i = $i+1;
            }
            $params_sql = substr($params_sql, 0, -1);

            $sql = "UPDATE $repo SET $params_sql";
            $this->db->query($sql);
            $this->db->execute();
        }

        // * Delete any model from DB
        protected function delete($repo, $id){
            if(!in_array($repo, $this->repos)){
                die('Repository not existing');
            }
            $obj_class = substr(ucwords($repo), 0, -1);
            if(!file_exists("../app/models/" . $obj_class . ".php")){
                die('Class not existing');
            }
            if(!is_int($id)){
                die('ID is not a number');
            }

            $object = new $obj_class();
            $id_field = $object->getIDfield();

            if($id == 0){
                $sql = "DELETE FROM $repo";
                $this->db->query($sql);
                $this->db->execute();
            }else{
                $sql = "SELECT * FROM $repo WHERE $id_field = $id";
                $this->db->query($sql);
                $this->db->execute();
                if($this->db->rowCount() != 0){
                    $sql = "DELETE FROM $repo WHERE $id_field = $id";
                    $this->db->query($sql);
                    $this->db->execute();
                }
            }
        }
    }
?>