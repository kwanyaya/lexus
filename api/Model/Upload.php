<?php

class Upload
{
    public $file;

    public $file_name;
    public $upload_format;

    public $destination;

    public $file_extension_match = [
            'img' => [
                'jpg',
                'jpeg',
                'png',
                'gif'
            ],
            'video' => [
                'mp4'
            ]
        ];
    
    public function __construct($file_name, $upload_file_info)
    {
        $this->file_name = $file_name;
        $this->upload_format = $upload_file_info;
    }

    public function initFiles(){
        foreach(REQ_FILE_KEYS as $rfk){
            if(!isset($_FILES[$rfk]) || $_FILES[$rfk]['size'] == 0){
                return failMsg($rfk." not found");
            }
            $this->file[] = $_FILES[$rfk];
            $this->destination[] = $this->upload_format['dir'].$this->genFileName($rfk).'.'.$this->upload_format['ext'];
            // echo $this->upload_format['dir'].$this->genFileName($rfk).'.'.$this->upload_format['ext'];
        }
    }

    private function genFileName($rfk){
        $diff = trim($rfk, $this->upload_format['type']);
        if(!$diff){
            return $this->file_name;
        } else{
            return $this->file_name.'_'.$diff;
        }
    }

    public function checkFileExist(){
        foreach($this->destination as $dest){
            if(!file_exists($dest)){
                return false;
            } 
        }
        return true;
    }

    public function checkExtension(){
        foreach($this->file as $file){
            $file_name = $file['name'];
            $file_ext =  explode(".", $file_name)[1];

            // if(!in_array($file_ext, $this->file_extension_match[$this->upload_format['type']])){
            //     return false;
            // } 
            if(!in_array($file_ext, $this->file_extension_match[REQ_FILE_INFO['type']])){
                return false;
            } 
        }
        return true;
    }

    public function uploadFile(){
        foreach($this->file as $f_key => $file){
            $file_tmp = $file['tmp_name'];
            if(!move_uploaded_file($file_tmp, $this->destination[$f_key])){
                return false;
            } 
        }
        return true;
    }

}