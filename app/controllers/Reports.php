<?php
    class Reports extends Controller{
        protected Report $reportModel;
        protected FileInfo $fileInfoModel;
        public function __construct()
        {
            $this->reportModel = $this->model('Report');
            $this->fileInfoModel = $this->model('FileInfo');
        }

        public function index(){
            redirect('files/upload');
        }

        public function report(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'description' => $_POST['description'],
                    'errors' => [
                    ],
                ];

                $r = ValidateUserInput::isEmpty($data, 3);
                
                if($r){
                    $data['id'] = $_POST['id'];

                    if($rec = $this->fileInfoModel->getFileInfoById($data['id'])){
                        
                        if($this->reportModel->add($data))
                            ajaxResponse(200, 'Report added successfully');
                        else ajaxResponse(406, 'Something went wrong');
                    }else{
                        ajaxResponse(406, 'There is no record for this id -> ' . $data['id']);    
                    }
                }else{
                    ajaxResponse(406, $data);
                }
            }
        }
    }