<?php
    class Files extends Controller{
        private File $fileModel;
        private FileInfo $fileInfoModel;
        private UserStorage $userStorageModel;
        
        public function __construct()
        {
            startSession('user');

            $this->fileModel = $this->model('File');
            $this->fileInfoModel = $this->model('FileInfo');
            $this->userStorageModel = $this->model('UserStorage');
        }

        /**
         *  Redirect to upload page
         */
        public function index(){
            redirect('files/upload');
        }


        /**
         *  All Uploaded Public Files Page
         */
        public function all($pageNo = 1){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $start = ($pageNo - 1) * 10;
                $result = $this->fileInfoModel->getFileInfosByName($_POST['search'], [$start, $start + 10], FileHelper::FILE_ATTR_PUBLIC);

                //usort($result['files'], [new SortHelper(false), 'sortFilesByDate']);
                $idArr = ModelHelper::getAllUserFileId($result['files']);

                if(count($idArr) > 0){
                    $detailedResult = $this->fileInfoModel->getAllFileInfoUserDetailById($idArr);
                    $result['files'] = ModelHelper::mergeFileInfoArr($result['files'], $detailedResult);
                }

                $result['search'] = $_POST['search'];
            }else{
                // Load view
                $start = ($pageNo - 1) * 10;
                $result = $this->fileInfoModel->getFileInfosByMode([$start, $start + 10], FileHelper::FILE_ATTR_PUBLIC);

                //usort($result['files'], [new SortHelper(false), 'sortFilesByDate']);
                $idArr = ModelHelper::getAllUserFileId($result['files']);

                if(count($idArr) > 0){
                    $detailedResult = $this->fileInfoModel->getAllFileInfoUserDetailById($idArr);
                    $result['files'] = ModelHelper::mergeFileInfoArr($result['files'], $detailedResult);
                }
                
            }
            
            $result['page_no'] = $pageNo;
            $this->view('files/all', $result);
        }

        /**
         *  Upload File Page
         */
        public function upload(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Prepare data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $userFile = $_FILES['uploaded_file'];

                $file = [
                    'name' => $userFile['name'],
                    'type' => $userFile['type'],
                    'path' => '',
                    'tmp_path' => $userFile['tmp_name'],
                    'size' => $userFile['size'],
                    'password' => '',
                    'description' => $_POST['description'],
                    'status' => FileHelper::FILE_ATTR_PUBLIC,
                ];

                $file['type'] = FileHelper::mimeToExt($file['type']);

                $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                if($userId){
                    // Registered user upload a file
                    $file['storage_id'] = $_SESSION['user_storage_id'];

                    if($file['size'] > FREE_FILE_SIZE)
                    {
                        ajaxResponse(406, 'File size is too large. File size must be max '
                                     . formatBytes(FREE_FILE_SIZE));
                    }
                }

                // Check if file upload as public
                $isPublic = isset($_POST['isPrivate']) ? false : true;

                if(!$isPublic){
                    // Create a hash
                    $file['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $file['status'] = FileHelper::FILE_ATTR_PRIVATE;
                    $dirName = 'private';
                }else $dirName = 'public';

                // full path to folder
                $path = FILESROOT . '\\' . $dirName;

                // full path to file
                $newFilePath = FileHelper::createRandomDir($path, true);
                
                // relative path to file folder
                $file['path'] = PROJECTROOT . '\\uploadedfiles\\' . $dirName . '\\' . basename($newFilePath);

                move_uploaded_file($file['tmp_path'], $newFilePath . '\\' . $file['name']);

                if($id = $this->fileModel->uploadFile($file))
                {
                    // File uploaded successfully
                    if(isLoggedIn()){
                        $this->userStorageModel->updateFileCount($_SESSION['user_storage_id'], '1');
                        $this->userStorageModel->updateUsedSize($_SESSION['user_storage_id'], $file['size']);
                    }
                    // Print file info id
                    ajaxResponse(200, $id);
                }else die("Something went wrong!");
            }else{
                $this->view('files/upload');
            }
        }

        /**
         *  File Info Page
         */
        public function info($fileInfoId){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                // Private file password check

                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                
                $data = [
                    'id' => $fileInfoId,
                    'password' => $_POST['password'],
                    'errors' => [
                        'password' => ''
                    ]
                ];
                

                $fileInfo = $this->fileInfoModel->getFileInfoById($fileInfoId);

                if($fileInfo){
                    if(password_verify($data['password'], $fileInfo->fileinfo_password)){
                        // Password is correct
                        $data = [
                            'file' => $fileInfo,
                        ];
                        
                        $this->view('files/info', $data);
                    }else{
                        // Password is incorrect
                        $data['errors']['password'] = 'Password is incorrect';

                        $this->view('files/private', $data);
                    }
                }
            }else{
                $fileInfo = $this->fileInfoModel->getFileInfoById($fileInfoId);
                
                if($fileInfo){
                    if($fileInfo->fileinfo_status == FileHelper::FILE_ATTR_PRIVATE){
                        // Private File

                        $data = [
                            'id' => $fileInfoId,
                            'password' => '',
                            'errors' => [
                                'password' => ''
                            ]
                        ];
    
                        $this->view('files/private', $data);
                    }else{
                        // Public File

                        $data = [
                            'file' => $fileInfo,
                        ];
                        
                        $this->view('files/info', $data);
                    }
                }else die("File Not Found");
            }
        }

        /** Success page */
        public function success($fileInfoId){
            $fileInfo = $this->fileInfoModel->getFileInfoById($fileInfoId);
            
            if($fileInfo){
                $data = [
                    'name' => $fileInfo->file_name,
                    'size' => $fileInfo->size,
                    'path' => URLROOT . '/files/info/' . $fileInfo->fileinfo_id
                ];
                $this->view('files/success', $data);
            }else die("Link is broken");
        }

        /** Delete file */
        public function delete($fileId){
            if(!isLoggedIn())
                redirect('users/login');
        
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $file = $this->fileModel->getFileById($fileId);

                if($file){
                    if($_SESSION['user_storage_id'] == $file->storage_id ){
                        if($this->fileModel->delete($fileId))
                        {
                            $this->userStorageModel->updateUsedSize($file->storage_id, '-' . $file->size);                            
                            $this->userStorageModel->updateFileCount($file->storage_id, -1);

                            redirect('users/storage/' . $_SESSION['user_storage_id']);
                        }
                        else die('Something went wrong');
                    }else die("You don't have access for this operation");
                }else die("File is not found");
            }
        }

        /**
         *  Edit File
         */
         public function edit($fileInfoId){
            if(!isLoggedIn())
                redirect('users/login');


            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $data['errors'] = [];
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                if(empty($_POST['filename'])){
                    $data['errors']['filename'] = 'File name can not be empty';
                }

                if($_POST['isPrivate'] == 'true'){
                    $_POST['password'] = trim($_POST['password']);

                    $l = strlen($_POST['password']);

                    if($l != 0 && $l < 4)
                    {
                        $data['errors']['password'] = 'Password must contain at least 4 characters'; 
                    }
                }

                if(count($data['errors']) > 0){
                    ajaxResponse(406, $data);
                }else{
                    $rec = $this->fileInfoModel->getFileInfoById($fileInfoId);

                    if($_SESSION['user_storage_id'] == $rec->storage_id){
                        if(!$this->fileModel->update([
                            'id' => $rec->file_id,
                            'filename' => $_POST['filename'],
                        ])) ajaxResponse(406, 'Something went wrong');
                        
                        $filePath = SERVERPUBLICROOT . '\\' . $rec->path . '\\' . $rec->file_name;

                        FileHelper::renameFile($filePath,$_POST['filename']);

                        $data = [
                            'id' => $fileInfoId,
                            'description' => $_POST['description'],
                        ];

                        if($_POST['isPrivate'] == 'true')
                        {
                            $data['status'] = FileHelper::FILE_ATTR_PRIVATE;
                            if(($rec->fileinfo_status == FileHelper::FILE_ATTR_PRIVATE || 
                                $rec->fileinfo_status == FileHelper::FILE_ATTR_PUBLIC) 
                                && !empty($_POST['password'])){
                                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                                $data['passStatus'] = 'change';
                            }else if($rec->fileinfo_status == FileHelper::FILE_ATTR_PUBLIC
                             && empty($_POST['password'])){
                                $data['errors']['password'] = 'Password must contain at least 4 characters'; 
                                ajaxResponse(406, $data);
                            }
                        }else{
                            $data['status'] = FileHelper::FILE_ATTR_PUBLIC;
                            $data['password'] = '';
                        }

                        if($this->fileInfoModel->update($data))
                            ajaxResponse(200, 'Succesfully edited');
                        else ajaxResponse(406, 'Something went wrong');
                    }else redirect('users/login');
                }
            }
         }
         
         /** Get file info */
         public function getFileInfo($fileInfoId){
            if(!isLoggedIn())
                redirect('users/login');

            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $rec = $this->fileInfoModel->getFileInfoById($fileInfoId);

                $rec = (array)$rec;

                // delete important values
                unset($rec['password']);
                unset($rec['fileinfo_password']);
                unset($rec['path']);

                if($rec){
                    echo json_encode($rec);
                }else die("File not found");
            }
         }
    }