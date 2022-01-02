<?php
    /**
     *  User Model
     */
    class User extends Model{
        /**
         *  Write user record to database
         *  @param array $data user input
         *  @return bool return true if write operation is successful , otherwise false
         */
        public function register($data){
            $sql = "INSERT INTO `user`(`name`, `username`, `email`, `password`, `status`) VALUES(:name, :username, :email, :password, :status)";

            // Creates query
            $this->db->query($sql);

            // Bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':username', $data['username']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':status', 1); // Change later to 0 for email confirmation

            $r1 = $this->db->execute();

            $id = $this->db->lastInsertId();

            // Create UserStorage record

            $sql = "INSERT INTO `userstorage`(`storage_size`, `user_id`) VALUES(:storage_size, :user_id)";
            $this->db->query($sql);
            $this->db->bind(':storage_size', FREE_STORAGE_SIZE);
            $this->db->bind(':user_id', $id);

            $r2 = $this->db->execute();

            return $r1 && $r2;
        }

        /**
         *  Get user from database
         *  @param string $email email address
         *  @param string $password password as plaintext
         *  @return mixed return user if there is a user associated this credentials, otherwise false
         */
        public function login($email, $password){
            $sql = "SELECT * FROM `user` WHERE `email` = :email";

            // Creates query
            $this->db->query($sql);

            // Bind value
            $this->db->bind(':email', $email);

            // Execute
            $user = $this->db->single();

            if(password_verify($password, $user->password)){
                return $user;
            }

            return false;
        }

        /**
         *  Find user by email address
         *  @param string $email email address
         *  @return bool return true if there is user associated this email, otherwise false
         */
        public function findUserByEmail($email){
            $sql = "SELECT * FROM `user` WHERE `email` = :email";

            // Creates query
            $this->db->query($sql);

            // Bind value
            $this->db->bind(':email', $email);

            // Execute
            $this->db->execute();

            return $this->db->rowCount() > 0;
        }

        /**
         *  Find user by username
         *  @param string @username username
         *  @return bool return true if there is user associated this username, otherwise false
         */
        public function findUserByUsername($username){
            $sql = "SELECT * FROM `user` WHERE `username` = :username";
            
            $this->db->query($sql);

            $this->db->bind(':username', $username);

            $this->db->execute();

            return $this->db->rowCount() > 0;
        }
    }