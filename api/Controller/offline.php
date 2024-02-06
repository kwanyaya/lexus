<?php

testMsgT("offline endpoint");

if(!isset($_POST['type']) || $_POST['type'] != 'insertUser'){
    failMsgT("invalid type");
}

$file_name = getRandomName();

if(DB_REQUIRE){
    // // ------------------- Filter Start -------------------
    // require_once(__DIR__.'/../Model/Filter.php');
    // $filter_model = new Filter();
    // $filter_result = $filter_model->dataValidation();
    // if(!resResult($filter_result)){
    //     failMsgT(resContent($filter_result));
    // }
    // // ------------------- Filter End -------------------
    
    // // ------------------- Database Start -------------------
    // require_once(__DIR__.'/../Model/User.php');
    // $db_model = new User(DB_TABLE);
    // $check_db_init = $db_model->load_status;
    // if(!$check_db_init){
    //     failMsgT('', '001');
    // }

    // $validate_columns = ['mobile', 'email'];
    // $validate_case = 'and';  // 'and' case for both exist; 'or' case for either one exist
    // $check_exist = $db_model->checkExistSqlStatement($_POST, $validate_columns, $validate_case);
    // if(!resResult($check_exist)){
    //     failMsgT(resContent($check_exist));
    // } else{
    //     if(resContent($check_exist)){
    //         failMsgT('Record Exist');
    //     }
    // }

    

    // $_POST[DB_FILE_COL] = $file_name;
    // $check_insert = $db_model->insertUser($_POST);
    // if(!resResult($check_insert)){
    //     failMsgT(resContent($check_insert));
    // }
    // // ------------------- Database End -------------------
}

    // ------------------- Upload Start -------------------
    require_once(__DIR__.'/../Model/Upload.php');
    $upload_model = new Upload($file_name, FILE_INFO);
    $upload_init_result = $upload_model->initFiles();
    if(!resResult($upload_init_result)){
        failMsgT(resContent($upload_init_result));
    }

    $check_exist_file = $upload_model->checkFileExist();
    if($check_exist_file){
        failMsgT('','003');
    }

    $check_match_type = $upload_model->checkExtension();
    if(!$check_match_type){
        failMsgT('','004');
    }

    $check_upload = $upload_model->uploadFile();
    if(!$check_upload){
        failMsgT('', '005');
    }
    // ------------------- Upload End -------------------
    
    
    // // ------------------- Email Start -------------------
    // require_once(__DIR__.'/../Model/Email.php');
    
    // $email_type = 'deafult'; // Define which email template
    // $email_type = 's1'; // Define which email template
    // $email_data = new stdClass();
    // $email_data->email = 'chris.yip@videinsight.asia'; // Email of receiver
    // $email_data->msg  = 'test email message'; // If no tmeplate, only msg
    // $email_data->link  = 'https://videinsight.asia'; // If template need moe than 1 custom values e.g, 's1'

    // $email_model = new Email($email_type, $email_data);
    // $email_result = $email_model->sendEmail();

    // if($email_result['status'] != 'success'){
    //     failMsgT('', '006');
    // }
    // // ------------------- Email End -------------------
    
    
    
    // // ------------------- Sms Start -------------------
    // require_once(__DIR__.'/../Model/Sms.php');
    
    // $sms_cc = '852';
    // $sms_mobile = '67162829';
    // $sms_data = new stdClass();
    // $sms_data->msg  = 'https://videinsight.asia'; // Dynamics msg for sms, static content need to edit inside model

    // $sms_model = new Sms($sms_cc, $sms_mobile, $sms_data);
    // $sms_result = $sms_model->sendSMS();

    // if(!$sms_result){
    //     failMsgT('', '007');
    // }
    // // ------------------- Sms End -------------------


    // // ------------------- ShortLink Start -------------------
    // require_once(__DIR__.'/../Model/ShortLink.php');

    // $long_url = 'https://google.com/'; // target link

    // $bitly_data = new stdClass();
    // $bitly_data->id = ''; // customised shorten link
    // $bitly_data->qr_code = false; // needs of qr code
    // $bitly_data->desc = 'tory burch sg'; // description of shorten link

    // $bitly_model = new ShortLink($long_url, $bitly_data);
    // $link_result = $bitly_model->link(); // shorten link

    // // ------------------- ShortLink End -------------------

successMsgT(RES_LINK.'?'.DB_FILE_COL.'='.$file_name);