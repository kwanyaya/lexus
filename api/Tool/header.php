<?php
// header("Access-Control-Allow-Origin:*");
// header("Access-Control-Allow-Headers:*"); 

// if(json_decode(file_get_contents('php://input'), true) != NULL ){
//     $_POST = json_decode(file_get_contents('php://input'), true);
// }

if(!isset($_POST['id']) || $_POST['id'] != REQ_ID){
    failMsgT("invalid id");
}
