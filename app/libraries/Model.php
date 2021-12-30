<?php

    /**
     * Base Model Class,
     * Create Database instance
     */
    class Model{
        protected $db;

        public function __construct()
        {
            $this->db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
    }