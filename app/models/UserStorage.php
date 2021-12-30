<?php
    class UserStorage extends Model{
        public function getStorageIdByUserId($id){
            $sql = "SELECT `id` FROM `userstorage` WHERE `user_id` = :id";
            
            $this->db->query($sql);

            $this->db->bind(':id', $id);

            $this->db->execute();

            return $this->db->single()->id;
        }
    }