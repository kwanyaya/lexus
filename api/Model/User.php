<?php
require_once(__DIR__.'/../config/Database.php');

class User extends Database
{
    
    protected $table;

    public function __construct($target_table_name) 
    { 
        parent::__construct();
        if($this->db != null){
            $this->load_status = true;
        }

        $this->table = $target_table_name;
    }

    public function checkExistSqlStatement($data_object, $target_columns, $case){
        $this->data_object = $data_object;

        $temp_case = 'get-'.$case;

        $select_result = $this->initSqlStatement($temp_case, '*', $target_columns);

        if(resResult($select_result)){
            $temp_res_content = resContent($select_result);
            return successMsg($temp_res_content->num_rows);
        }
        return $select_result;
    }

    private function filterReqParams($data_object){
        if(isset($data_object['id'])){
            unset($data_object['id']);
        }
        if(isset($data_object['type'])){
            unset($data_object['type']);
        }
        return $data_object;
    }

    private function getDataKey($data_object){
        $data_require = array();
        foreach($data_object as $req_key => $req_value){
            $data_require[] = $req_key;
        }
        return $data_require;
    }

    public function checkData($data_object, $data_require){
        $this->data_object = $data_object;

        $check_result = $this->initSqlStatement('check', $data_require, "");

        return $check_result;
    }


    public function insertUser($data_object){
        $do = $this->filterReqParams($data_object);
        $dr = $this->getDataKey($do);
        
        $this->data_object = $do;

        // return true;
        return $this->initSqlStatement('insert', $dr, '');
    }

    public function getUser($data_object){
        $this->data_object = $data_object;
        return $this->initSqlStatement('get', '*', '');
    }
    
    // public function getUser($data_object){
    //     $get_statement = 'SELECT * FROM '.$this->table;
    //     try{
    //         $this->stmt = $this->db->prepare($get_statement);
    //         $this->stmt->execute();
    //     } catch (PDOException $e){
    //         return failMsg('Error: '. $e->getMessage().' Error Code : '.$e->getCode());
    //     }
    //     // please return selectResult() for select cases
    //     return successMsg($this->selectResult());  
    // }

    
}