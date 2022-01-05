<?php 
    class Admins extends Controller{
        protected Admin $adminModel;
        protected FileInfo $fileInfoModel;
        protected File $fileModel;
        protected UserStorage $userStorageModel;

        public function __construct()
        {
            startSession('admin');

            $this->adminModel = $this->model('Admin');
            $this->fileInfoModel = $this->model('FileInfo');
            $this->fileModel = $this->model('File');
            $this->userStorageModel = $this->model('UserStorage');
        }

        public function index(){
            if(!isAdminLoggedIn())
                redirect('admins/login');
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                
            }else{
                $this->view('admins/index');
            }
        }

        public function allfiles($pageNo = 1){
            if(!isAdminLoggedIn())
                redirect('admins/login');

            if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            }else{
                $start = ($pageNo - 1) * 10;
                $result = $this->fileInfoModel->getAllFileInfo([$start, $start + 10]);

                $idArr = ModelHelper::getAllUserFileId($result['files']);

                if(count($idArr) > 0){
                    $detailedResult = $this->fileInfoModel->getAllFileInfoUserDetailById($idArr);
                    $result['files'] = ModelHelper::mergeFileInfoArr($result['files'], $detailedResult);
                }

                $result['page_no'] = $pageNo;
                $this->view('admins/allfiles', $result);
            }
        }

        public function login(){
            if(isAdminLoggedIn())
                redirect('admins/index');
            
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

        public function changefilestatus($fileInfoId){
            if(!isAdminLoggedIn())
                redirect('admins/login');
            
            if($_SESSION['admin_access_status'] != AdminHelper::ADMIN_STATUS_WRITE)
                ajaxResponse(200, 'You don\'t have access for this operation');
            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $newStat = $_POST['status'] == 'true' ? FileHelper::FILE_ATTR_ACTIVE : FileHelper::FILE_ATTR_INACTIVE;

                $rec = $this->fileInfoModel->getFileInfoById($fileInfoId);

                $data = [
                    'id' => $fileInfoId,
                    'status' => $newStat,
                ];

                if($data['status'] == FileHelper::FILE_ATTR_INACTIVE){
                    $data['old_status'] = $rec->fileinfo_status;
                }else{
                    if(is_null($rec->old_status)){
                        $data['status'] = FileHelper::FILE_ATTR_PUBLIC;
                    }else{
                        $data['status'] = $rec->old_status;
                    }

                    $data['old_status'] = NULL;
                }

                if($this->fileInfoModel->changeFileStatus($data)){
                    ajaxResponse(200, 'file status successfully changed');
                }else ajaxResponse(406, 'Something went wrong');
            }
        }

        public function deletefile($fileId){
            if(!isAdminLoggedIn())
                redirect('admins/login');
            
            if($_SESSION['admin_access_status'] != AdminHelper::ADMIN_STATUS_WRITE)
              die('You don\'t have access for this operation');

            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $rec = $this->fileModel->getFileById($fileId);

                if($rec){
                    $this->fileModel->delete($fileId);
                    FileHelper::deleteFile(SERVERPUBLICROOT . '\\' . $rec->path, $rec->name);
                    $this->userStorageModel->updateUsedSize($rec->storage_id, '-' . $rec->size);                            
                    $this->userStorageModel->updateFileCount($rec->storage_id, -1);

                    flash('file_remove_success', $rec->name . ' deleted');
                    redirect('admins/allfiles');
                }else die('Something went wrong');
            }
        }
    }