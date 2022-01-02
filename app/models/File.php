<?php
    class File extends Model{
        /**
         *  Create a file record for guest user
         *  @param array $file file data
         *  @return bool return false if any execution fails, otherwise false 
         */
        public function uploadFile($file){
            // Creates a query for file
            $this->db->query('INSERT INTO `file`(`name`, `path`, `size`, `type`) VALUES(:name, :path, :size, :type)');

            // Bind values
            $this->db->bind(':name', $file['name']);
            $this->db->bind(':path', $file['path']);
            $this->db->bind(':size', $file['size']);
            $this->db->bind(':type', $file['type']);

            // Execute
            $r1 = $this->db->execute();
            
            // if execution fails return false
            if(!$r1)
                return false;

            $fileId = $this->db->lastInsertId();
            
            // Creates a query for file info
            $this->db->query('INSERT INTO `fileinfo`(`file_id`,`password`, `storage_id`, `description`, `status`) VALUES(:file_id, :password, :storage_id, :description, :status)');

            // Bind values
            $this->db->bind(':file_id', $fileId);
            $this->db->bind(':password', $file['password']);
            isset($file['storage_id']) ? 
                        ($this->db->bind(':storage_id', $file['storage_id'])) : 
                        ($this->db->bind(':storage_id', null));
            $this->db->bind(':description', $file['description']);
            $this->db->bind(':status', $file['status']);
            
            // if execution fails return false
            if(!$this->db->execute())
                return false;
            return $this->db->lastInsertId();
        }

        /**
         *  Delete a file record
         *  @param int $fileId file id
         *  @return bool return false if any execution fails, otherwise false 
         */
        public function delete($fileId){
            $sql = "DELETE FROM `file` WHERE `id` = :file_id";
            $this->db->query($sql);
            $this->db->bind(':file_id', $fileId);
            
            return $this->db->execute();
        }

        /**
         *  Get a file record
         *  @param int $fileId file id
         *  @return mixed return false if any execution fails, otherwise return record 
         */
        public function getFileById($fileId){
            $sql = "SELECT * FROM `file` INNER JOIN `fileinfo` 
                                            ON `fileinfo`.`file_id` = `file`.`id`
                                        WHERE `file`.`id` = :file_id";
            $this->db->query($sql);
            $this->db->bind(':file_id', $fileId);
           

            return  $this->db->execute() ? $this->db->single() : false;
        }
    }