<?php
    /*
        * PDO Database Class
        * Features:
        * -> Connect to database
        * -> Create prepared statements
        * -> Bind values
        * -> Return rows and results
    */
    class Database {
        private $host = DB_HOST;
        private $user = DB_USERNAME;
        private $password = DB_PASSWORD;
        private $db_name = DB_DATABASE;

        private $dbh;
        private $stmt;
        private $error;

        public function __construct() {
            $dsn = "mysql:host=" . $this->host . ';dbname=' . $this->db_name;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION/*,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ*/
            );

            // Create PDO instance
            try {
                $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
            } catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        // Prepare statement with query
        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }

        // Bind values, for explaination search for PDO statement bindValue function in PHP docs
        public function bind($param, $value, $type = null){
            switch(true){
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
                    break;
            }
            $this->stmt->bindValue($param, $value, $type);
        }

        // Execute the prepared statement
        public function execute() {
            return $this->stmt->execute();
        }

        // Get result set as array of objects
        public function resultSet() {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        // Get single record as object
        public function single() {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        // Get row count
        public function rowCount(){
            return $this->stmt->rowCount();
        }

        // * Get last inserted ID
        public function lastInsertID(){
            return $this->dbh->lastInsertID();
        }
    }
?>