<?php

Class Database{
    public $load_status = false;

    public $db = null;
    public $stmt = null;

    // protected $db_host = "localhost";
    // protected $db_name = "videinsil0_first";
    // protected $db_user = "videinsil0_firstadmin";
    // protected $db_password = "PMsfwh64x";
    // protected $db_charset = "utf8";

    protected $db_host = "localhost";
    protected $db_name = "test_2023";
    protected $db_user = "root";
    protected $db_password = "";
    protected $db_charset = "utf8";

    public $data_object = array();
    public $bind_param = array();
    

    public $result;

    function __construct () {
        try {
          $this->db = new PDO(
            "mysql:host=".$this->db_host.";dbname=".$this->db_name.";charset=".$this->db_charset,
            $this->db_user, $this->db_password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
          ]);
        } catch (Exception $ex) { 
            testMsgT('Failed to create db connection');
        }
        $this->initResult();
    }

    private function initResult(){
        $this->result = new stdClass();
        $this->result->row = array();
        $this->result->rows = array();
        $this->result->num_rows = 0;
    }

    public function checkContain($data_require){
        // var_dump($data_require);
        if(!empty($data_require)){
            foreach($data_require as $dr){
                if($dr == "updated_at"){
                    $this->data_object[$dr] = getCurrentTime();
                }
                if(!isset($this->data_object[$dr])){
                    return failMsg($dr." not found");
                }
            }
        }
        return successMsg();
    }

    public function translateSqlStatement($arr, $case){
        $connectives = '';
        switch ($case) {
            case 'and':
                $connectives = " AND ";
                break;
            case 'or':
                $connectives = " OR ";
                break;
            case 'comma':
                $connectives = ", ";
                break;
            case 'comma-and':
                $connectives = ", ";
                break;
            case 'comma-and-bind':
                $connectives = ", ";
                break;
            default:
                testMsgT("translate no connectives");
        }

        if(!empty($arr)){
            $p_statement = "";

            foreach($arr as $k => $a){
                if(isset($this->data_object[$a])){
                    $this->bind_param[':'.$a] = $this->data_object[$a];
                }
                if(!($case == 'comma-and' || $case == 'comma-and-bind')){
                    $p_statement .= $a.' = :'.$a.' ';
                }else{
                    if($case == 'comma-and'){
                        $p_statement .= $a;
                        unset($this->bind_param[':'.$a]);
                    } else{
                        $p_statement .= ':'.$a;
                    }
                }
                if($k+1 != count($arr)){
                    $p_statement .= $connectives;
                }
            }
            return $p_statement;

        } 
    }

    public function bindParam(){
        foreach($this->bind_param as $key => $bp){
            $this->stmt->bindValue($key, $bp);
        }
    }

    public function genSelectSql($data_require, $data_condition, $case = ''){
        
        $require = $data_require;
        if($require != "*"){
            $require = $this->translateSqlStatement($data_require, 'comma-and');
        }
        
        $select_statement = "SELECT $require FROM $this->table ";
        if(!empty($data_condition)){
            if($case == 'or'){
                $condition = $this->translateSqlStatement($data_condition, 'or');
            } else{
                $condition = $this->translateSqlStatement($data_condition, 'and');
            }
            $select_statement .= "WHERE $condition";
        }

        return $this->db->prepare($select_statement);
    }

    public function genUpdateSql($data_require, $data_condition){
        $require = $this->translateSqlStatement($data_require, 'comma');
        
        $update_statement = "UPDATE $this->table SET $require ";
        if(!empty($data_condition)){
            $condition = $this->translateSqlStatement($data_condition, 'and');
            $update_statement .= "WHERE $condition";
        }

        // var_dump($update_statement);
        // var_dump($this->bind_param);
        // var_dump($this->data_object);


        // return $update_statement;
        return $this->db->prepare($update_statement);
    }

    public function genInsertSql($data_require){
        $require = $this->translateSqlStatement($data_require, 'comma-and');
        $bind = $this->translateSqlStatement($data_require, 'comma-and-bind');

        $insert_statement = "INSERT INTO $this->table ( $require ) VALUES ( $bind )";

        // var_dump($insert_statement);

        return $this->db->prepare($insert_statement);
    }

    public function genDeleteSql($data_condition){

        $delete_statement = "DELETE FROM $this->table ";
        
        if(!empty($data_condition)){
            $condition = $this->translateSqlStatement($data_condition, 'and');
            $delete_statement .= "WHERE $condition";
        }

        return $this->db->prepare($delete_statement);
    }

    public function selectResult(){
        $data = array();

        while ($row = $this->stmt->fetch(\PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        $this->result->row = (isset($data[0]) ? $data[0] : array());
        $this->result->rows = $data;
        $this->result->num_rows = $this->stmt->rowCount();

        return $this->result;
    }


    public function initSqlStatement($action, $data_require, $data_condition){
        $this->bind_param = array();
        
        switch ($action) {
            case 'get':
            case 'get-and':
            case 'get-or':
                testMsgT("gen get sql");

                $contain_result = $this->checkContain($data_condition);
                if(!resResult($contain_result)){
                    return $contain_result;
                }

                $case = '';
                if(strpos($action, '-')){
                    $case = explode('-', $action)[1];
                }

                try {
                    $this->stmt = $this->genSelectSql($data_require, $data_condition, $case);
                    $this->stmt->execute($this->bind_param);
                } catch (PDOExcepton $e) {
                    return failMsg('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
                }

                return successMsg($this->selectResult());
                break;
            case 'update':
                testMsgT("gen update sql");

                if(!empty($data_require)){
                    $contain_result = $this->checkContain($data_require);
                    if(!resResult($contain_result)){
                        return $contain_result;
                    }
                }
                $contain_result = $this->checkContain($data_condition);
                if(!resResult($contain_result)){
                    return $contain_result;
                }

                try {
                    $this->stmt = $this->genUpdateSql($data_require, $data_condition);
                    $execution = $this->stmt->execute($this->bind_param);
                } catch (PDOExcepton $e) {
                    return failMsg('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
                }

                return successMsg($execution);
                break;
            case 'insert':
                testMsgT("gen insert sql");

                $contain_result = $this->checkContain($data_require);
                if(!resResult($contain_result)){
                    return $contain_result;
                }

                try {
                    $this->stmt = $this->genInsertSql($data_require);
                    $execution = $this->stmt->execute($this->bind_param);
                } catch (PDOExcepton $e) {
                    return failMsg('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
                }

                return successMsg($execution);
                break;
            case 'delete':
                testMsgT("gen delete sql");

                $contain_result = $this->checkContain($data_require);
                if(!resResult($contain_result)){
                    return $contain_result;
                }

                try {
                    $this->stmt = $this->genDeleteSql($data_require);
                    $execution = $this->stmt->execute($this->bind_param);
                } catch (PDOExcepton $e) {
                    return failMsg('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
                }

                return successMsg($execution);
                break;
            case 'truncate':
                testMsgT("gen truncate sql");

                try {
                    $this->stmt = $this->db->prepare("TRUNCATE TABLE $this->table");
                    $execution = $this->stmt->execute($this->bind_param);
                } catch (PDOExcepton $e) {
                    return failMsg('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
                }

                return successMsg($execution);
                break;
            case 'check':
                testMsgT("gen check sql");
                if(!empty($data_require)){
                    $contain_result = $this->checkContain($data_require);
                    if(!resResult($contain_result)){
                        return $contain_result;
                    }
                }
                if(!empty($data_condition)){
                    $contain_result = $this->checkContain($data_condition);
                    if(!resResult($contain_result)){
                        return $contain_result;
                    }
                }
                return $contain_result;
                break;
            default:
                testMsgT("gen no action");
        }

        
    }

    

}