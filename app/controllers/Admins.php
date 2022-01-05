<?php 
    class Admins extends Controller{
        protected Admin $adminModel;

        public function __construct()
        {
            startSession('admin');

            $this->adminModel = $this->model('Admin');    
        }

        public function index(){
            if(!isAdminLoggedIn())
                redirect('admins/login');
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
            }else{
                $this->view('admins/index');
            }
        }

        public function login(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'errors' => [
                        'username' => '',
                        'password' => '',
                    ]
                ];

                $validation = ValidateUserInput::isEmpty($data, 2);

                if($validation){
                    // validation passed

                    if($rec = $this->adminModel->login($data['username'], $data['password'])){
                        $this->createAdminSession($rec);
                    }else{
                        flash('admin_login_fail', 'Credentials are incorrect', 'alert alert-danger');
                        $this->view('admins/login', $data);
                    }
                }else{
                    // load view with errors
                    $this->view('admins/login', $data);
                }
            }else{
                $data = [
                    'username' => '',
                    'password' => '',
                    'errors' => [
                        'username' => '',
                        'password' => '',
                    ]
                ];

                $this->view('admins/login', $data);
            }
        }

         /**
         *  Create admin session and redirect to index page
         *  @param object $admin admin as obj
         */
        public function createAdminSession($admin){
            $_SESSION['admin_id'] = $admin->id;
            $_SESSION['admin_username'] = $admin->username;
            $_SESSION['admin_access_status'] = $admin->access_status;

            redirect('admins/index');
        }

        /**
         *  Logout from account and redirect to login page
         */
        public function logout(){
            unset($_SESSION['admin_id']);
            unset($_SESSION['admin_username']);
            unset($_SESSION['admin_access_status']);
            flash('logout_success', 'You logged out.');
            redirect('admins/login');
        }
    }