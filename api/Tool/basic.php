<?php

function testMsgT($msg){
    // echo "--".$msg."\n";
}

function failMsg($msg){
    return [
        'status' => "fail",
        'content' => $msg
    ];
}

function failMsgT($msg="Connection Error.", $code=''){
    $final_msg = $msg;
    if($code){
        $final_msg .= 'Connection Error. Error Code: '.$code;
    }
    echo json_encode([
        'status' => "fail",
        'content' => $final_msg
    ]);
    exit();
}


function successMsg($msg = 'success'){
    return [
        'status' => "success",
        'content' => $msg
    ];
}

function successMsgT($msg = 'success'){
    echo json_encode([
        'status' => "success",
        'content' => $msg
    ]);
    exit();
}


function resResult($msg){
    if($msg['status'] == 'fail'){
        return false;
    }
    return true;
}

function resContent($msg){
    return $msg['content'];
}

function getCurrentTime(){
    date_default_timezone_set('Asia/Hong_Kong');
    $date = date('Y-m-d H:i:s', time());
    return $date;
}

function getRandomName($n = 5){
    return bin2hex(random_bytes($n));
}

function createUid($key1, $key2, $limit = 16){
    return substr(base_convert(sha1(uniqid(mt_rand()) . $key1 . $key2), 16, 36), 0, $limit);
}
