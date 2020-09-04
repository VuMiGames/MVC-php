<?php
    class PostRepository extends Repository {
        private $db;

        public function __construct(){
            $this->db = new Database();
        }
    }