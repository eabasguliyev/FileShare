<?php
    class SortHelper{
        private bool $asc;

        public function __construct($asc)
        {
            $this->asc = $asc;
        }
        /**
         *  Sort By date, default is asc order
         */
        public function sortFilesByDate($f1, $f2){
            return $this->asc ? $f1->file_created_at > $f2->file_created_at : $f1->file_created_at < $f2->file_created_at; 
        }
    }