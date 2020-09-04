<?php
    class Repository {
        protected $db;
        protected $repos;

        public function __construct($repos=[]){
            $this->db = new Database();
            $this->repos = $repos;
        }

        public function addRepo($repo){
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
            $this->db->execute();
            $this->db->resultSet();
            echo "<pre>"; print_r($this->db->resultSet()); echo "</pre>";
        }

        protected function getByID($repo, $id, $id_str = "id"){
            if(!array_key_exists($repo, $this->repos)){
                die('Repository not exist');
            }
            if(strpos(trim($repo), ' ') !== false || strpos(trim($id), ' ') !== false || strpos(trim($id_str), ' ') !== false)
            {
                die('Access denied');
            }
            
            $sql = "SELECT * FROM $repo WHERE :id_str = :id";
            $this->db->query($sql);
            $this->db->bind(":id_str", $id_str);
            $this->db->bind(":id", $id);
            return $this->db->single();
        }
    }
?>