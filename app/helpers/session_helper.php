<?php
    session_start();

    /**
     *  Flash message helper
     *  @param string $name key
     *  @param string $message value
     *  @param string $class html element classes
     */
    /*
      Example use
        flash('register_success', 'You are now registered', 'alert alert-success');
      Display in view
        echo flash('register_success');
    */ 
    function flash($name, $message = '', $class = 'alert alert-success'){
        if(!empty($name)){
            if(!empty($message)){
                $_SESSION[$name] = $message;
                $_SESSION[$name . '_class'] = $class;
            }elseif(empty($message) && !empty($_SESSION[$name])){
                $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
                
                echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name . '_class']);
            }else
                return false;
            return true;
        }
        return false;
    }

    /**
     *  Check if the user is logged in
     *  @return bool return true if logged in, otherwise false
     */
    function isLoggedIn(){
        return isset($_SESSION['user_id']);
    }

    function isAdminLoggedIn()
    {
        return isset($_SESSION['admin_id']);
    }