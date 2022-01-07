<?php
    class Report extends Model{
        public function add($data){
            $sql = "INSERT INTO `report` (`name`, `email`, `description`, `fileinfo_id`) 
                            VALUES (:name, :email, :desc, :id)";

            $this->db->query($sql);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':desc', $data['description']);
            $this->db->bind(':id', $data['id']);

            return $this->db->execute();
        }

        public function getAll(array $range){
            $sql = "SELECT COUNT(*) as `report_count` FROM `report`";

            $this->db->query($sql);
            $this->db->execute();
            $total = $this->db->single()->report_count;

            $sql = "SELECT *, `report`.`id` AS `report_id`,
                                `report`.`name` AS `report_name`,
                                `report`.`email` AS `report_email`,
                                `report`.`description` AS `report_description`,
                                `file`.`name` AS `file_name`
                    FROM `report` 
                    INNER JOIN `fileinfo` ON `report`.`fileinfo_id` = `fileinfo`.`id`
                    INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                    ORDER BY `report`.`created_at` DESC
                    LIMIT :start, :count";

            $this->db->query($sql);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);
            $this->db->execute();
            $dataSet = $this->db->resultSet();
            $pageCount = 0;
            if($this->db->rowCount() != 0)
            {
                $pageCount = ceil($total / $range[1]);
            }

            return [
                'reports' => $dataSet,
                'total_count' => $total, 
                'page_count' => $pageCount 
            ];
        }

        public function getReportById($id){
            $sql = "SELECT *, `report`.`id` AS `report_id`,
                                `report`.`name` AS `report_name`,
                                `report`.`email` AS `report_email`,
                                `report`.`description` AS `report_description`,
                                `fileinfo`.`description` AS `fileinfo_description`,
                                `fileinfo`.`status` AS `fileinfo_status`,
                                `file`.`id` AS `file_id`,
                                `file`.`name` AS `file_name`,
                                `file`.`type` AS `file_type`
                    FROM `report` 
                    INNER JOIN `fileinfo` ON `report`.`fileinfo_id` = `fileinfo`.`id`
                    INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                    WHERE `report`.`id` = :id";

            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $this->db->execute();

            $rec = $this->db->single();

            if(!$rec)
                return false;

            if(is_null($rec->storage_id))
                return $rec;

            $sql = "SELECT *, `report`.`id` AS `report_id`,
                    `report`.`name` AS `report_name`,
                    `report`.`email` AS `report_email`,
                    `report`.`description` AS `report_description`,
                    `fileinfo`.`description` AS `fileinfo_description`,
                    `fileinfo`.`status` AS `fileinfo_status`,
                    `file`.`id` AS `file_id`,
                    `file`.`name` AS `file_name`,
                    `file`.`type` AS `file_type`
                    FROM `report` 
                    INNER JOIN `fileinfo` ON `report`.`fileinfo_id` = `fileinfo`.`id`
                    INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                    INNER JOIN `userstorage` ON `fileinfo`.`storage_id` = `userstorage`.`id`
                    INNER JOIN `user` ON `userstorage`.`user_id` = `user`.`id`
                    WHERE `report`.`id` = :id";

            
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $this->db->execute();

            return $this->db->single();
        }

        public function delete($id){
            $sql = "DELETE FROM `report` WHERE id = :id";

            $this->db->query($sql);
            $this->db->bind(':id', $id);

            return $this->db->execute();
        }

        public function getAllByFileName(string $search, array $range){
            $sql = "SELECT COUNT(*) as `report_count` FROM `report`
                                INNER JOIN `fileinfo` ON `report`.`fileinfo_id` = `fileinfo`.`id`
                                INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                                WHERE `file`.`name` LIKE CONCAT('%', :search, '%')";

            $this->db->query($sql);
            $this->db->bind(':search', $search);
            $this->db->execute();
            $total = $this->db->single()->report_count;

            $sql = "SELECT *, `report`.`id` AS `report_id`,
                                `report`.`name` AS `report_name`,
                                `report`.`email` AS `report_email`,
                                `report`.`description` AS `report_description`,
                                `file`.`name` AS `file_name`
                    FROM `report` 
                    INNER JOIN `fileinfo` ON `report`.`fileinfo_id` = `fileinfo`.`id`
                    INNER JOIN `file` ON `fileinfo`.`file_id` = `file`.`id`
                    WHERE `file`.`name` LIKE CONCAT('%', :search, '%') 
                    ORDER BY `report`.`created_at` DESC
                    LIMIT :start, :count";

            $this->db->query($sql);
            $this->db->bind(':search', $search);
            $this->db->bind(':start', $range[0]);
            $this->db->bind(':count', $range[1]);
            $this->db->execute();
            $dataSet = $this->db->resultSet();
            $pageCount = 0;
            if($this->db->rowCount() != 0)
            {
                $pageCount = ceil($total / $range[1]);
            }

            return [
                'reports' => $dataSet,
                'total_count' => $total, 
                'page_count' => $pageCount 
            ];
        }
    }