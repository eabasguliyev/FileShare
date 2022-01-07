<?php
    class FileInfo extends Model{
        /**
         *  Get file info
         *  @return array files and total_count
         */
        public function getAllFileInfo(array $range){
            // $range[1] -= $range[0];

            $this->db->query("SELECT COUNT(*) as file_count FROM `fileinfo`");
            $this->db->execute();
            $totalFile = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, 
                            `file`.`name` as `file_name`, 
                            `fileinfo`.`id` as `fileinfo_id`, 
                            `fileinfo`.`status` as `fileinfo_status`, 
                            `file`.`id` as `file_id` FROM `fileinfo` 
                            INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` 
                            ORDER BY `file`.`created_at` 
                            DESC LIMIT :start, :count";

            // Creates a query
            $this->db->query($sql);

            // Bind values
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            // Execute query
            $this->db->execute();
            // $resultcount = $this->db->rowCount();

            $dataSet = $this->db->resultSet();

            $pageCount = 0;
            if($this->db->rowCount() != 0)
            {
                $pageCount = ceil($totalFile / $range[1]);
            }
            return [
                'files' => $dataSet,
                'total_count' => $totalFile, 
                'page_count' => $pageCount 
            ];
        }

        public function getFileInfosByName(string $search, array $range){
            $range[1] -= $range[0];
            $sql = "SELECT COUNT(*) as file_count FROM `fileinfo`
                                INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                                 WHERE `file`.`name` LIKE CONCAT('%', :search, '%')";

            $this->db->query($sql);
            $this->db->bind(':search', $search);
            $this->db->execute();
            $totalFile = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, 
                            `file`.`name` as `file_name`, 
                            `fileinfo`.`id` as `fileinfo_id`, 
                            `fileinfo`.`status` as `fileinfo_status`, 
                            `file`.`id` as `file_id` FROM `fileinfo` 
                            INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` 
                            WHERE `file`.`name` LIKE CONCAT('%', :search, '%') ORDER BY `file`.`created_at` 
                            DESC LIMIT :start, :count";

            // Creates a query
            $this->db->query($sql);

            // Bind values
            $this->db->bind(':search', $search);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            // Execute query
            $this->db->execute();

            $dataSet = $this->db->resultSet();

            $pageCount = 0;
            if($this->db->rowCount() != 0)
            {
                $pageCount = ceil($totalFile / $range[1]);
            }
            return [
                'files' => $dataSet,
                'total_count' => $totalFile, 
                'page_count' => $pageCount 
            ];
        }

        public function getFileInfosByMode(array $range, int $mode){
            // $range[1] -= $range[0];

            $this->db->query("SELECT COUNT(*) as file_count FROM `fileinfo` WHERE `fileinfo`.`status` = :status");
            $this->db->bind(':status', $mode);
            $this->db->execute();
            $totalFile = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, 
                            `file`.`name` as `file_name`, 
                            `fileinfo`.`id` as `fileinfo_id`, 
                            `file`.`id` as `file_id` FROM `fileinfo` 
                            INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` 
                            WHERE `fileinfo`.`status` = :status ORDER BY `file`.`created_at` 
                            DESC LIMIT :start, :count";

            // Creates a query
            $this->db->query($sql);

            // Bind values
            $this->db->bind(':status', $mode);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            // Execute query
            $this->db->execute();
            // $resultcount = $this->db->rowCount();

            $dataSet = $this->db->resultSet();

            $pageCount = 0;
            if($this->db->rowCount() != 0)
            {
                $pageCount = ceil($totalFile / $range[1]);
            }
            return [
                'files' => $dataSet,
                'total_count' => $totalFile, 
                'page_count' => $pageCount 
            ];
        }

        public function getAllFileInfoUserDetailById($fileInfoIdArr){
            $sql = "SELECT *, `file`.`created_at` AS `file_created_at`,
                                         `file`.`name` AS `file_name`,
                                          `fileinfo`.`id` AS `fileinfo_id`, 
                                          `fileinfo`.`status` as `fileinfo_status`, 
                                          `file`.`id` AS `file_id` FROM `fileinfo` 
                                          INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                                          INNER JOIN `userstorage` ON `userstorage`.`id` = `fileinfo`.`storage_id` 
                                        INNER JOIN `user` ON `userstorage`.`user_id` = `user`.`id` WHERE `fileinfo`.`id` IN (";
            $sql .= implode(",", $fileInfoIdArr) . ")";
            
            $this->db->query($sql);
            $this->db->execute();
            return $this->db->resultSet();
        }

        public function getFileInfosByNameAndMode(string $search, array $range, int $mode){
            $range[1] -= $range[0];
            $sql = "SELECT COUNT(*) as file_count FROM `fileinfo`
                                INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                                 WHERE `fileinfo`.`status` = :status  
                                    AND `file`.`name` LIKE CONCAT('%', :search, '%')";

            $this->db->query($sql);
            $this->db->bind(':status', $mode);
            $this->db->bind(':search', $search);
            $this->db->execute();
            $totalFile = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` as `file_created_at`, 
                            `file`.`name` as `file_name`, 
                            `fileinfo`.`id` as `fileinfo_id`, 
                            `file`.`id` as `file_id` FROM `fileinfo` 
                            INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id` 
                            WHERE `fileinfo`.`status` = :status AND `file`.`name` LIKE CONCAT('%', :search, '%') ORDER BY `file`.`created_at` 
                            DESC LIMIT :start, :count";

            // Creates a query
            $this->db->query($sql);

            // Bind values
            $this->db->bind(':status', $mode);
            $this->db->bind(':search', $search);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            // Execute query
            $this->db->execute();
            // $resultcount = $this->db->rowCount();

            $dataSet = $this->db->resultSet();

            $pageCount = 0;
            if($this->db->rowCount() != 0)
            {
                $pageCount = ceil($totalFile / $range[1]);
            }
            return [
                'files' => $dataSet,
                'total_count' => $totalFile, 
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
                             `file`.`id` AS `file_id`,
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


        public function getUserFilesByStorageId($storageId, array $range, array $modes){
            $modeArgsStr = implode(',', array_map(function($key){
                                return ':' . $key . '_arg';
                            }, array_keys($modes)));

            $sql = "SELECT COUNT(*) AS file_count FROM `fileinfo` WHERE `storage_id` = :storage_id
                                             AND `fileinfo`.`status` IN (" . $modeArgsStr . ")";
            $this->db->query($sql);
            $this->db->bind(':storage_id', $storageId);
            for ($i=0, $l = count($modes); $i < $l; $i++) { 
                $this->db->bind(':' . $i . '_arg', $modes[$i]);
             }

            $this->db->execute();
            $filesCount = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` AS `file_created_at`, 
                            `file`.`name` AS `file_name`, 
                            `fileinfo`.`id` AS `fileinfo_id`,
                             `file`.`id` AS `file_id` 
                             FROM `fileinfo`
                              INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                               WHERE `storage_id` = :storage_id AND `fileinfo`.`status` IN ("
                               . $modeArgsStr
                               .  
                               ") ORDER BY `file`.`created_at` 
                               DESC LIMIT :start, :count;";

            $this->db->query($sql);
            $this->db->bind(':storage_id', $storageId);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            for ($i=0, $l = count($modes); $i < $l; $i++) { 
                $this->db->bind(':' . $i . '_arg', $modes[$i]);
             }

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

        public function getUserFilesByName($storageId, $search, array $range, array $modes){
            $modeArgsStr = implode(',', array_map(function($key){
                return ':' . $key . '_arg';
            }, array_keys($modes)));

            $sql = "SELECT COUNT(*) AS file_count FROM `fileinfo` 
                                INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                                WHERE `storage_id` = :storage_id AND
                                 `fileinfo`.`status` IN (" . $modeArgsStr . ") AND `file`.`name` 
                                 LIKE CONCAT('%', :search, '%')";

            $this->db->query($sql);
            $this->db->bind(':storage_id', $storageId);
            $this->db->bind(':search', $search);

            for ($i=0, $l = count($modes); $i < $l; $i++) { 
                $this->db->bind(':' . $i . '_arg', $modes[$i]);
            }

            $this->db->execute();
            $filesCount = $this->db->single()->file_count;

            $sql = "SELECT *, `file`.`created_at` AS `file_created_at`,
                             `file`.`name` AS `file_name`, 
                             `fileinfo`.`id` AS `fileinfo_id`, 
                             `file`.`id` AS `file_id` FROM `fileinfo`
                              INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                               WHERE `storage_id` = :storage_id AND `fileinfo`.`status` IN ( " 
                               . $modeArgsStr
                               .  ") AND `file`.`name` LIKE CONCAT('%', :search, '%') 
                               ORDER BY `file`.`created_at` DESC LIMIT :start, :count;";


            $this->db->query($sql);
            $this->db->bind(':storage_id', $storageId);
            $this->db->bind(':search', $search);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);

            for ($i=0, $l = count($modes); $i < $l; $i++) { 
               $this->db->bind(':' . $i . '_arg', $modes[$i]);
            }
            
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

        public function update($data){
            $stat = (isset($data['passStatus']) && $data['passStatus'] == 'change') 
            || $data['status'] == FileHelper::FILE_ATTR_PUBLIC;

            $sql = "UPDATE `fileinfo` SET `status` = :status, `description` = :desc";
            $sql .= $stat ? ", `password` = :pass" : '';
            $sql .= " WHERE `id` = :id";

            $this->db->query($sql);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':desc', $data['description']);

            if($stat)
                $this->db->bind(':pass', $data['password']);
            $this->db->bind(':id', $data['id']);

            return $this->db->execute();
        }

        public function changeFileStatus($data){
            $sql = "UPDATE `fileinfo` SET `status` = :status, `old_status` = :old_status 
                            WHERE `id` = :id";

            $this->db->query($sql);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':old_status', $data['old_status']);
            $this->db->bind(':id', $data['id']);
            
            return $this->db->execute();
        }
    }