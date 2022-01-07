<?php
    /**
    * App Core Class,
    * Creates URL & loads core controller.
    * URL Format - /controller/method/params
    */
    class Core{
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];
        public static $lang = 'en';
        public static $langs = [];
        /**
         * Parse url and call controller method
         */
        public function __construct()
        {
            $url = $this->getUrl();

            $index = 0;

            // Check lang
            self::$langs = [
                'az',
                'en',
            ];

            if(isset($url[$index]) && in_array($url[$index], self::$langs)){
                self::$lang = strtolower($url[$index]);

                setcookie('lang', self::$lang, 0, "/" . PROJECTROOT);

                unset($url[$index]);
                $index++;
            }else{
                self::$lang = $_COOKIE['lang'];
            }

            // Look in controllers
            if(isset($url[$index]) && file_exists('../app/controllers/' . $url[$index] . '.php')){
                // If exists, set as controller
                $this->currentController = ucfirst($url[$index]);

                // Unset
                unset($url[$index]);
                $index++;
            }

            // Require the controller
            require_once '../app/controllers/' . $this->currentController . '.php';

            // Instantiate controller class
            $this->currentController = new $this->currentController;
            
            // Check for second part of url
            if(isset($url[$index])){
                // Check to see if method exists in controller
                if(method_exists($this->currentController, $url[$index])){
                    $this->currentMethod = $url[$index];

                    // Unset index
                    unset($url[$index]);
                    $index++;
                }

                // Get params
                $this->params = $url ? array_values($url) : [];
            }

            // Call a callback with array of params
            call_user_func_array([ $this->currentController, $this->currentMethod ], $this->params);
        }

        /**
         *  Parse url
         *  @return array parts of url
         */
        public function getUrl(){
            if(isset($_GET['url'])){
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);

                return $url;
            }
        }
    }