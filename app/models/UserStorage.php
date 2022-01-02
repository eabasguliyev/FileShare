<?php
    class UserStorage extends Model{
        /**
         *  Get Storage record
         *  @param int $id user id
         *  @return object storage record
         */
        public function getStorageByUserId($id){
            $sql = "SELECT * FROM `userstorage` WHERE `user_id` = :id";
            
            $this->db->query($sql);

            $this->db->bind(':id', $id);

            $this->db->execute();

            return $this->db->single();
        }
        /**
         *  Update Storage Used Size
         *  @param int $storageId storage id
         *  @param string $bytes bytes (can be negative number)
         *  @return bool return false if execution fails, otherwise return true
         */
        public function updateUsedSize($storageId, $bytes){
            $sql = "UPDATE `userstorage` SET `used_size` = `used_size` + :bytes WHERE `id` = :storage_id";

            $this->db->query($sql);
            $this->db->bind(':bytes', $bytes);
            $this->db->bind(':storage_id', $storageId);

            return $this->db->execute();
        }

        /**
         *  Update File Count Size
         *  @param int $storageId storage id
         *  @param int $count file count (can be negative number)
         *  @return bool return false if execution fails, otherwise return true
         */
        public function updateFileCount($storageId, $count){
            $sql = "UPDATE `userstorage` SET `file_count` = `file_count` + :count WHERE `id` = :storage_id";

            $this->db->query($sql);
            $this->db->bind(':count', $count);
            $this->db->bind(':storage_id', $storageId);

            return $this->db->execute();
        }
    }