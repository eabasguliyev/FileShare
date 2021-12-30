<?php
    /**
     *  Simple page redirect
     *  @param string $page page name
     */
    function redirect($page){
        header('Location: ' . URLROOT . '/' . $page);
    }