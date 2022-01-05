<?php
    class Pages extends Controller{
        public function __construct()
        {
            startSession('user');
        }
        public function index(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

            }else{
                redirect('files/upload');
            }
        }
    }