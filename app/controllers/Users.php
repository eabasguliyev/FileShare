<?php
    class Users extends Controller{
        private User $userModel;
        private UserStorage $userStorageModel;
        private FileInfo $fileInfoModel;
        
        /**
         *  Initialize models
         */
        public function __construct()
        {
            $this->userModel = $this->model('User');
            $this->userStorageModel = $this->model('UserStorage');
            $this->fileInfoModel = $this->model('FileInfo');
        }

        /** 
         *  User register
         */
        public function register(){
            if(isLoggedIn())
                redirect('files/upload');

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Process form

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Init data
                $data = [
                    'name' => trim($_POST['name']),
                    'username' => trim($_POST['username']),
                    'email' => trim($_POST['email']),
                    'password' => $_POST['password'],
                    'confirm_password' => $_POST['confirm_password'],
                    'errors' => [
                        'name' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                        'confirm_password' => '',
                    ],
                ];

                // Validate inputs
                $r1 = ValidateUserInput::isEmpty($data, 5);
                $r2 = ValidateUserInput::validatePassword($data, 6);
                $r3 = !$this->userModel->findUserByEmail($data['email']);
                $r4 = !$this->userModel->findUserByUsername($data['username']);

                if(!$r3){
                    $data['errors']['email'] = "Email is already registered";
                }

                if(!$r4){
                    $data['errors']['username'] = "Username is already taken";
                }

                // Make sure validation is pass
                if($r1 && $r2 && $r3 && $r4){
                    // Validation passed

                    // Hash password
                    $data['password'] = password_Hash($data['password'], PASSWORD_DEFAULT);
                    
                    // Register user
                    if($this->userModel->register($data)){
                        flash('register_success', 'You are registered, you can login now');
                        redirect('users/login');
                    }else{
                        die('Something went wrong.');
                    }
                }else{
                    // Load form with errors
                    $this->view('users/register', $data);
                }
            }else{
                // Load form

                // Init data
                $data = [
                    'name' => '',
                    'username' => '',
                    'email' => '',
                    'password' => '',
                    'confirm_password' => '',
                    'errors' => [
                        'name' => '',
                        'username' => '',
                        'email' => '',
                        'password' => '',
                        'confirm_password' => '',
                    ],
                ];

                $this->view('users/register', $data);
            }
        }

        /** 
         *  User login
         */
        public function login(){
            if(isLoggedIn())
                redirect('files/upload');

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Init data
                $data = [
                    'email' => trim($_POST['email']),
                    'password' => $_POST['password'],
                    'errors' => [
                        'email' => '',
                        'password' => '',
                    ],
                ];

                // Validate inputs
                $r1 = ValidateUserInput::isEmpty($data, 2);
                $r2 = $this->userModel->findUserByEmail($data['email']);

                if(!empty($data['email']) && !$r2){
                    $data['errors']['email'] = "There is no account associated this email";
                }

                // Make sure validation is pass
                if($r1 && $r2){
                    // Validation passed

                    // Check and set logged in user
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                    if($loggedInUser){
                        $loggedInUser = (array)$loggedInUser;
                        $loggedInUser['storageId'] = $this->userStorageModel->getStorageByUserId($loggedInUser['id'])->id;
                        
                        // Create user session
                        $this->createUserSession($loggedInUser);
                    }else{
                        $data['errors']['password'] = 'Password incorrect';
                    }
                }
                
                // Load form with errors
                $this->view('users/login', $data);
            }else{
                // Load view
                $data = [
                    'email' => '',
                    'password' => '',
                    'errors' => [
                        'email' => '',
                        'password' => '',
                    ],
                ];

                $this->view('users/login', $data);
            }
        }

        /**
         *  User Storage
         */
        public function storage($storageId, $pageNo = 1){
            if($storageId != $_SESSION['user_storage_id'])
                redirect('users/storage/' . $_SESSION['user_storage_id']);

            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {

            }else{
                $start = ($pageNo - 1) * 10;
                
                $storage = $this->userStorageModel->getStorageByUserId($_SESSION['user_id']);

                $result = $this->fileInfoModel->getUserFilesByStorageId($storage->id, [$start, 10]);

                $result['page_no'] = $pageNo;
                $result['used_size'] = formatBytes($storage->used_size);
                $result['file_count'] = $storage->file_count;
                $this->view('users/storage', $result);
            }
        }
        

        /**
         *  Create user session and redirect to index page
         *  @param array $user user as array
         */
        public function createUserSession($user){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_storage_id'] = $user['storageId'];

            redirect('pages/index');
        }

        /**
         *  Logout from account and redirect to login page
         */
        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_name']);
            unset($_SESSION['user_email']);
            flash('logout_success', 'You logged out. If you want to manage your files please log in');
            redirect('users/login');
        }
    }