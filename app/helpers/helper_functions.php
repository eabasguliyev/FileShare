<?php
    function getGUID(){
        if (function_exists('com_create_guid')){
            return str_replace('-', '', trim(com_create_guid(), '{}'));
        }
        else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $uuid = substr($charid, 0, 8)
                .substr($charid, 8, 4)
                .substr($charid,12, 4)
                .substr($charid,16, 4)
                .substr($charid,20,12);
            return $uuid;
        }
    }

    function downloadFile($filePath){
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false); // required for certain browsers 
        header('Content-Type: ' . mime_content_type($filePath));

        header('Content-Disposition: attachment; filename="'. basename($filePath) . '";');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($filePath));


        $handle = fopen($filePath, "r") or die("Couldn't get handle");
        
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                print($buffer);
            }
            fclose($handle);
        }
    }