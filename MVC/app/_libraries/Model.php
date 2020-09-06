<?php
    class Model {
        protected $id;
        protected $id_field = "ID";

        public function setID($id){
            $this->id = $id;
        }

        public function setIDfield($id_field){
            $this->id_field = $id_field;
        }

        public function getID(){
            return $this->id;
        }

        public function getIDfield(){
            return $this->id_field;
        }
    }
?>