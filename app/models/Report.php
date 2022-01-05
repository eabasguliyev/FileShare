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

        public function getAll(){
            $sql = "SELECT *, `report`.`id` AS `report_id`,
                                `report`.`name` AS `report_name`,
                                `report`.`email` AS `report_email`,
                                `report`.`description` AS `report_description`,
                                `fileinfo`.`description` AS `fileinfo_description`,
                                `fileinfo`.`status` AS `fileinfo_status`,
                                 FROM `report` 
                    INNER JOIN `fileinfo` ON `report`.`fileinfo_id` = `fileinfo`.`id`
                    INNER JOIN `userstorage` ON `fileinfo`.`storage_id` = `userstorage`.`id`
                    INNER JOIN `user` ON `userstorage`.`user_id` = `user`.`id`
                    ";

            $this->db->query($sql);
            $this->db->execute();
            
            return $this->db->resultSet();
        }
    }