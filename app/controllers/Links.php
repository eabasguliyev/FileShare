<?php
    class Links extends Controller{
        private Link $linkModel;
        public function __construct()
        {
            $this->linkModel = $this->model('Link');
        }

        public function index(){
            redirect('files/upload');
        }

        /**
         *  Create link record POST request page
         *  @param int $fileInfoId file info id
         */
        public function create($fileInfoId){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $guid = $this->linkModel->create($fileInfoId);
                if($guid){
                    $linkObj = $this->linkModel->getLinkByGuid($guid);
                    $downloadLink = URLROOT . '/links/download/' . $linkObj->guid . '/' . $linkObj->name;

                    echo $downloadLink;
                }else die('Something went wrong');   
            }
        }

        /**
         *  Download file GET request page
         *  @param string $guid unique identifier of link
         */
        public function download($guid){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $linkObj = $this->linkModel->getLinkByGuid($guid);

                if($linkObj){
                    // file full path
                    $filePath =  SERVERPUBLICROOT . '\\' . $linkObj->file_path . '\\' . $linkObj->name;
                    
                    downloadFile($filePath);
                    $this->linkModel->incrementDownloadCount($linkObj->file_info_id);
                }else die("Download link expired");
            }
        }
    }