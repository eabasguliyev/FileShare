<?php
    class FileInfo extends Model{
        /**
         *  Get all file info
         *  @return array files and total_count
         */
        public function getFileInfosByMode(array $range, int $mode){
            $range[1] -= $range[0];

            $this->db->query("SELECT COUNT(*) as file_count FROM `fileinfo` WHERE `fileinfo`.`status` = :status AND `fileinfo`.`storage_id` IS NULL");
            $this->db->bind(':status', $mode);
            $this->db->execute();
            $guestFileCount = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, `file`.`name` as `file_name`, `fileinfo`.`id` as `fileinfo_id`, `file`.`id` as `file_id` FROM `fileinfo` INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` AND `fileinfo`.`storage_id` IS NULL WHERE `fileinfo`.`status` = :status ORDER BY `file`.`created_at` DESC LIMIT :start, :count ;";

            // Creates a query
            $this->db->query($sql);

            // Bind values
            $this->db->bind(':status', $mode);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            // Execute query
            $this->db->execute();
            $resultcount = $this->db->rowCount();

            $dataSet1 = $this->db->resultSet();

            $this->db->query("SELECT COUNT(*) as file_count FROM `fileinfo` WHERE `fileinfo`.`status` = :status AND `fileinfo`.`storage_id` IS NOT NULL");
            $this->db->bind(':status', $mode);
            $this->db->execute();
            $registeredFileCount = $this->db->single()->file_count;
            
            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, `file`.`name` as `file_name`, `fileinfo`.`id` as `fileinfo_id`, `file`.`id` as `file_id` FROM `fileinfo` INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` INNER JOIN `userstorage` ON `userstorage`.`id` = `fileinfo`.`storage_id` INNER JOIN `user` ON `userstorage`.`user_id` = `user`.`id` WHERE `fileinfo`.`status` = :status ORDER BY `file`.`created_at` DESC LIMIT :start, :count;";

            $this->db->query($sql);

            $this->db->bind(':status', $mode);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            $this->db->execute();
            $resultcount += $this->db->rowCount();

            $dataSet2 = $this->db->resultSet();

            $total = $guestFileCount + $registeredFileCount;

            $pageCount = 0;
            if($resultcount != 0)
            {
                $pageCount = ceil($total / $resultcount);
            }
            return [
                'files' => array_merge($dataSet1, $dataSet2),
                'total_count' => $total, 
                'page_count' => $pageCount 
            ];
        }

        /** Get file info record by file info id
         *  @param int $id file info id
         *  @return mixed file info record, if record not found return false
         */
        public function getFileInfoById($id){
            $sql = "SELECT *,`fileinfo`.`id` AS `fileinfo_id`,
                             `file`.`created_at` AS `file_created_at`,
                              `file`.`name` AS `file_name`,
                              `fileinfo`.`password` AS `fileinfo_password`,
                              `fileinfo`.`status` AS `fileinfo_status`
                                            FROM `fileinfo` INNER JOIN `file` 
                                                    ON `file`.`id` = `fileinfo`.`file_id` 
                                            WHERE `fileinfo`.`id` = :id";

            $this->db->query($sql);

            $this->db->bind(':id', $id);

            $this->db->execute();

            $rec = $this->db->single();

            if($rec){
                if(!is_null($rec->storage_id)){
                    $sql = 'SELECT *,`fileinfo`.`id` AS `fileinfo_id`,
                                     `file`.`created_at` AS `file_created_at`,
                                     `file`.`name` AS `file_name`,
                                  `fileinfo`.`password` AS `fileinfo_password`,
                                    `fileinfo`.`status` AS `fileinfo_status`
                                                        FROM `fileinfo` INNER JOIN `file` 
                                                        ON `file`.`id` = `fileinfo`.`file_id`
                                                     INNER JOIN `userstorage` 
                                                        ON `userstorage`.`id` = `fileinfo`.`storage_id`
                                                     INNER JOIN `user` 
                                                        ON `user`.`id` = `userstorage`.`user_id` 
                                                        WHERE `fileinfo`.`id` = :id';
                    $this->db->query($sql);

                    $this->db->bind(':id', $id);

                    $this->db->execute();

                    $rec = $this->db->single();

                    return $rec ? $rec : false;
                }
                return $rec;
            }
            return false;
        }

        public function getUserFilesByStorageId($storageId, array $range){
            $sql = "SELECT COUNT(*) AS file_count FROM `fileinfo` WHERE `storage_id` = :storage_id";
            $this->db->query($sql);
            $this->db->bind(':storage_id', $storageId);
            $this->db->execute();
            $filesCount = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, `file`.`name` as `file_name`, `fileinfo`.`id` as `fileinfo_id`, `file`.`id` as `file_id` FROM `fileinfo` INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` WHERE `storage_id` = :storage_id ORDER BY `file`.`created_at` DESC LIMIT :start, :count;";

            $this->db->query($sql);
            $this->db->bind(':storage_id', $storageId);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);
            $this->db->execute();

            $files = $this->db->resultSet();
            $resultCount = $this->db->rowCount();
            $pageCount = 0;

            if($resultCount != 0){
                $pageCount = ceil($filesCount / $range[1]);
            }
            
            return [
                'files_count' => $filesCount,
                'files' => $files,
                'page_count' => $pageCount,
            ];
        }
    }