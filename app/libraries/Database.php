<?php
    /**
     * PDO Database Class,
     * Connect to database,
     * Create prepared statements,
     * Bind values,
     * Return rows and results.
     */
    class Database{
        private $dbh;
        private $stmt;
        private $error;

        /** Create DSN string, PDO instance and
         *  if no error occurs set instance to $dbh,
         *  otherwise set error message to $error.
         */
        public function __construct($host, $user, $pass, $dbname){
            // Create DSN and options
            $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ];

            // Create PDO instance with dsn
            try{
                $this->dbh = new PDO($dsn, $user, $pass, $options);
            }catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        /**
         *  Prepare statement with query
         *  @param string $sql - sql query
         */ 
        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }


        /** 
         *  Bind value
         *  @param string $param - param name
         *  @param mixed $value - value
         *  @param integer $type - type of value
         */
        public function bind($param, $value, $type = null){
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }

            $this->stmt->bindValue($param, $value, $type);
        }

        /**
         *  Execute the prepared statement
         *  @return bool return true if query execution is success,
         *  otherwise false
         */
        public function execute(){
            return $this->stmt->execute();
        }

        /**
         *  Get result set as array of objects
         *  @return array result set
         */
        public function resultSet(){
            $this->execute();
            return $this->stmt->fetchAll();
        }

        /**
         *  Get single record as object
         *  @return stdClass object
         */
        public function single(){
            $this->execute();
            return $this->stmt->fetch();
        }

        /** 
         * Get result row count
         * @return integer row count
         */
        public function rowCount(){
            return $this->stmt->rowCount();
        }

        /** 
         *  Get last inserted record id
         *  @return int id
         */ 
        public function lastInsertId(){
            return $this->dbh->lastInsertId();
        }
    }