<?php
    /**
     * Base Controller,
     * Load the models and views.
     */

     class Controller{
         /** Load model and instantiate
          *  @param string $model model name
          *  @return $model class object
         */
        public function model($model){
            // Require model file
            require_once '../app/models/' . $model . '.php';

            // Instantiate model
            return new $model();
        }

        /**
         * Load view
         * @param string $view view name
         * @param array $data data for view
         */
        public function view($view, $data = []){
            $fileName = '../app/views/' . $view . '.php';

            // Check for view file
            if(file_exists($fileName)){
                require_once $fileName;
            }else{
                // View does not exist
                die('View does not exist');
            }
        }
     }