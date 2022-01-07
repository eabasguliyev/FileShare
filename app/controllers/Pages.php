<?php
    class Pages extends Controller{
        public function __construct()
        {
            startSession('user');
        }
        public function index(){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                redirect('files/upload');
            }
        }

        public function terms(){
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                $content = FileHelper::getPageContentByLang('pages/terms', Core::$lang);
                
                $data = [
                    'content' => $content,
                ];
                $this->view('pages/terms', $data);
            }
        }
    }