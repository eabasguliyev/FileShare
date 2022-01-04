<?php
    class ModelHelper{
        public static function prepareFiles($files){
            
        }

        public static function getAllUserFileId($fileInfoArr){
            $idArr = [];

            foreach ($fileInfoArr as $fileInfo) {
                if(!is_null($fileInfo->storage_id)){
                    $idArr[] = $fileInfo->fileinfo_id;
                }
            }

            return $idArr;
        }

        public static function mergeFileInfoArr($arr1, $arr2){
            for ($i=0, $l = count($arr1); $i < $l; $i++) { 
                if(!is_null($arr1[$i]->storage_id)){
                    $obj = current(array_filter($arr2, function($el) use($arr1, $i){
                        if($el->fileinfo_id == $arr1[$i]->fileinfo_id)
                            return $el;
                    }));
                    $arr1[$i] = $obj;
                }
            }

            return $arr1;
        }
    }