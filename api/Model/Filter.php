<?php

Class Filter{
    
    public $lang = 'en';
    public $lang_error = [
        'en' => [
            'alpha' => 'Invalid format, alphabet only.',
            'num' => 'Invalid format, number only.',
            'email' => 'Invalid format.',
            'date' => 'Invalid format.',
            'alnum' => 'Invalid format.',
            'expired' => 'Invalid format.',
        ]
    ];
    public $error_msg;

    public function dataValidate($key, $value){

        switch($key) {
            case 'name':
                if (!isAlpha($value)) {
                    $this->errorCaseFailMsg('alpha');
                }
                break;
            
            case 'mobile':
                if (!isNum($value)) {
                    $this->errorCaseFailMsg('num');
                }
                break;
    
            case 'email':
                if(!isEmail($value)){
                    $this->errorCaseFailMsg('email');
                }
                break;
    
            case 'date':
                if(!isDate($value)){
                    $this->errorCaseFailMsg('date');
                }
                break;
    
            case 'uid':
                if(!isAlnum($value)){
                    $this->errorCaseFailMsg('alnum');
                }
                break;
    
            case 'end_at':
                $current_date = date('Y-m-d', strtotime(getCurrentTime()));
                
                if ($current_date > $value){
                    $this->errorCaseFailMsg('expired');
                }
                break;
            
            default: 
                return true;
        }
    }

    public function __construct($lang = 'en'){
        $this->lang = $lang;
    }

    public function dataValidation(){
        foreach($_POST as $req_key => $req_value){
            $this->dataValidate($req_key, $req_value);
        }
        if($this->error_msg){
            return failMsg($this->error_msg);
        } else{
            return successMsg();
        }
    }

    private function errorCaseFailMsg($err_case){
        $this->error_msg = $this->lang_error[$this->lang][$err_case];
    }
}


function isAlpha($param){
    return ctype_alpha($param);
}

function isNum($param){
    return is_numeric($param);
}

function isAlnum($param){
    return ctype_alnum($param);
}

function isEmail($param){
    return filter_var($param, FILTER_VALIDATE_EMAIL);
}

function isDate($param){
    return strtotime($param);
}
