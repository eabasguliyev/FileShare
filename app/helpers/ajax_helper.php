<?php
    function ajaxResponse(int $code, $data){
        http_response_code($code);
        die(json_encode($data));
    }