<?php
    class ValidateUserInput{
        /**
         *  Check inputs is empty
         *  @param array $data input data
         *  @param int $count input counts
         *  @return bool if any input empty return false, otherwise true;
         */
        public static function isEmpty(&$data, $count){
            $flag = true;
            $i = 0;
            foreach($data as $key => $value){
                if($i < $count){
                    if(empty($value))
                    {
                        $flag = false;
                        $data['errors'][$key] = 'Please enter ' . implode(' ', explode('_', $key));
                    }
                }else break;

                $i++;
            }
            return $flag;
        }

        /**
         *  Validate password length, any space, and confirm password is match
         *  @param array $data user input
         *  @param int $passwordLength password length
         *  @return bool if any validation fails return false, otherwise true
         */
        public static function validatePassword(&$data, $passwordLength){
            $flag = true;
            if(strlen($data['password']) < $passwordLength){
                $data['errors']['password'] = 'Password must be at least 6 characters';
                $flag = false;
            }
            else if(str_contains($data['password'], ' ')){
                $data['errors']['password'] = "Password can't be  contain any spaces";
                $flag = false;
            }

            if($data['password'] != $data['confirm_password']){
                $data['errors']['confirm_password'] = "Passwords don't match";
                $flag = false;
            }
            return $flag;
        }
    }