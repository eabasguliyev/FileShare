<?php
    class Admin extends Model{
        public function login($username, $password){
            $sql = "SELECT * FROM `admin` WHERE `username` = :username;";
            $this->db->query($sql);
            $this->db->bind(':username', $username);
            $this->db->execute();

            $rec = $this->db->single();


            return $rec ? (password_verify($password, $rec->password) ? $rec : false) : false;
        }
    }