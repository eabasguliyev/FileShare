<?php
    class Link extends Model{
        /**
         *  Create link record
         *  @param int $fileInfoId file info id
         *  @return string guid of created record
         */
        public function create($fileInfoId){
            $sql = "INSERT INTO `generatedlinks`(`guid`, `file_info_id`) VALUES(:guid, :file_info_id)";

            $this->db->query($sql);

            $guid = getGUID();

            $this->db->bind(':guid', $guid);
            $this->db->bind(':file_info_id', $fileInfoId);

            $this->db->execute();

            return $guid;
        }

        /**
         *  Get link record by guid
         *  @param string $guid identifier of link record
         *  @return object link record
         */
        public function getLinkByGuid($guid){
            $sql = "SELECT *, `file`.`path` AS `file_path`,
                            `fileinfo`.`id` AS `file_info_id`
                                                 FROM `generatedlinks` INNER JOIN `fileinfo` 
                                                    ON `fileinfo`.`id` = `generatedlinks`.`file_info_id`
                                                    INNER JOIN `file` 
                                                    ON `file`.`id` = `fileinfo`.`file_id`
                                                   WHERE `guid` = :guid";

            $this->db->query($sql);

            $this->db->bind(':guid', $guid);

            $this->db->execute();

            return $this->db->single();
        }

        /**
         *  Increment link download count
         *  @param string $fileInfoId file info record id
         *  @return bool return false if execution fails, otherwise true
         */
        public function incrementDownloadCount($fileInfoId){
            $sql = "UPDATE `fileinfo` SET `fileinfo`.`download_count` = `fileinfo`.`download_count` + 1
                                     WHERE `id` = :id";

            $this->db->query($sql);
            $this->db->bind(':id', $fileInfoId);

            return $this->db->execute();
        }
    }