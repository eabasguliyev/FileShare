<?php
    class Files extends Controller{
        private File $fileModel;
        private FileInfo $fileInfoModel;
        private UserStorage $userStorageModel;
        
        public function __construct()
        {
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

            }else{
                // Load view
                $start = ($pageNo - 1) * 10;
                $result = $this->fileInfoModel->getFileInfosByMode([$start, $start + 10], FileHelper::FILE_ATTR_PUBLIC);

                usort($result['files'], [new SortHelper(false), 'sortFilesByDate']);

                $result['page_no'] = $pageNo;

                $this->view('files/all', $result);
            }
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
                    'status' => 0,
                ];

                $file['type'] = FileHelper::mimeToExt($file['type']);

                $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

                if($userId){
                    // Registered user upload a file
                    $file['storage_id'] = $_SESSION['user_storage_id'];

                    if($file['size'] > FREE_FILE_SIZE)
                    {
                        http_response_code(406);
                        echo 'File size is too large. File size must be max ' . formatBytes(FREE_FILE_SIZE);
                        return;
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
                    $this->userStorageModel->updateFileCount($_SESSION['user_storage_id'], '1');
                    $this->userStorageModel->updateUsedSize($_SESSION['user_storage_id'], $file['size']);
                    // Print file info id
                    echo $id;
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
    }