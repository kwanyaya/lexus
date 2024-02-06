# CR - Offline Share
> #### All code has been implemented in comment at template file
> `*` means that parameters must be mentioned when implement the file
---
# Request - Case for request data format ( Unity / Client-side )

## api/config/conf.php
* ### Request ID `REQ_ID` *
  * State the request key `id`
    * `define('REQ_ID', '{event_id}');`
    * `id` checking located at `api/Tool/header.php`

* ### Request File 
  > The request file(s) must be the same type of media
  * #### Request File Specifications `REQ_FILE_INFO`
    * State file type by media `type`, options
      * Image Case
        * `define('REQ_FILE_INFO', [ 'type' => 'img' ]);`
      * Video Case
        * `define('REQ_FILE_INFO', [ 'type' => 'video' ]);`

  * #### Request File Keys `REQ_FILE_KEYS`
    * State the received file key with matched file type
    * If the received file is an image, the file key will be equal or similar to `img`

    * ##### Single File Case
      * Image Case
        * `define('REQ_FILE_KEYS', [ 'img' ]);`
      * Video Case
        * `define('REQ_FILE_KEYS', [ 'video' ]);`

    * ##### Mutilple Files Case
      * Images Case
        * `define('REQ_FILE_KEYS', [ 'img1', 'img2' ]);`
      * Videos Case
        * `define('REQ_FILE_KEYS', [ 'video1', 'video2' ]);`

---
# Upload - Case for upload data format
> in case the request file and the upload file are different type

> the upload file(s) will be the same type of extension
## api/config/conf.php

* ### <a id="fileinfo"></a>Uploaded File Specifications `FILE_INFO` 
  * ```
    define('FILE_INFO', 
          [
              'dir' => '../uploads/',
              'type' => 'video', 
              'ext' => 'mp4'
          ]
    );
    ```
  * State the file destination on `dir`
  * State the file type on `type`
    * Image Case
      * `'type' => 'img'`
    * Video Case
      * `'type' => 'video'`
  * State the file extension on `ext`
    * Image Case
      * eg. `'ext' => 'png'`
    * Video Case
      * eg. `'ext' => 'mp4'`

* ### Uploaded Files `FILE_KEYS`
  * State the uploaded file key with matched file type
    * eg. `define('FILE_KEYS', [ 'img' ] );`
    * purpose for getting the uploaded file(s) on sharing page
  * #### Upload File Name
    * Handling the file name for multiple uploaded files case
    * Uploaded file name will be named as the differences between file type
    * For example:
      * `'img'` will result a `${filename}.png`
      * `'img1'` will result a `${filename}_1.png`
  * #### Single File Case
    * Image Case
      * `define('FILE_KEYS', [ 'img' ] );`
        * the uploaded file will be equal to `${filename}.png`
    * Video Case
      * `define('FILE_KEYS', [ 'video' ] );`
        * the uploaded file will be equal to `${filename}.mp4`
  * #### Mutilple Files Case
    * Image Case
      * `define('FILE_KEYS', [ 'img1', 'img2' ] );`
        * the uploaded file will be equal to `${filename}_1.png`, `${filename}_2.png`
    * Video Case
      * `define('FILE_KEYS', [ 'video1', 'video2' ] );`
        * the uploaded file will be equal to `${filename}_1.mp4`, `${filename}_2.mp4`
  
## api/Controller/offline.php

* ### Upload Model
  * initial model `Upload(${filename}, FILE_INFO)`
    * `${filename}` -> customise
    * [`FILE_INFO`](#fileinfo) @api/config/conf.php
  * initial file `initFiles()`
    * only return fail case by [failMsg](#failmsg)
  * check file exist `checkFileExist()`
    * return `true` if file exist
    * return `false` otherwise
  * check file extension match the file type `checkExtension()`
    * return `true` if file matched
    * return `false` otherwise
  * upload file `uploadFile()`
    * return `true` if file successfully uploaded
    * return `false` otherwise


---
# Response - Case for response data

## api/config/conf.php

* ### Response Link `RES_LINK`
  * State the response share link
    * `define('RES_LINK', '${url}');` 
* ### Response Link `DB_FILE_COL`
    * Query string on share link 
      * `define('DB_FILE_COL', 'uid');`
    * For example:
      *   `${url}/?uid=${filename}`
      *   `uid` = `DB_FILE_COL`

---
# Share Page - Case for social share, preview and download file

## api/config/conf.php

* ### Share Page Information `SHARE_INFO`
  * ```
    define('SHARE_INFO',
          [
              'html_title' => 'html title', 
              'share_title' => 'share title', 
              'share_description' => 'share description',
              'download_file_name' => 'XXX-event-2023'.'.'.FILE_INFO['ext'],
          ]    
    );
    ```
  *   Web title `html_title`
  *   Share title `share_title`
  *   Share description `share_description`
  *   Downloaded file name `download_file_name`

## index.php
* ### Edit layout and assets file if needed
  * Uneccessary to implement file type
  * For multiple files case, slider layout will be shown
  * Hide the icon in `share-container` if that function is not require
  * Change the `$upload_link` when the `dir` @ [`FILE_INFO`](#fileinfo) has changed
  
## share/index.php
* Sharing page if needed

---
# Database - Case for save data

## api/config/conf.php

* ### Database Require `DB_REQUIRE` 
  * State `DB_REQUIRE` to define the use of database connection
    * Use db
      * `define('DB_REQUIRE', true);`
    * No db
      * `define('DB_REQUIRE', false);`
      * you can ignore other db config at this case

* ### <a id="dbtable"></a>Database Table `DB_TABLE`
  * State database table
    * `define('DB_TABLE', '${db_table}');`

* ### File Column `DB_FILE_COL`
  * eg. `define('DB_FILE_COL', 'uid');`
  * State saved file name column on table
    * database table must contains a column named `uid`
  * Query string on share link
    * shared link = `${RES_LINK}/?uid=${filename}`

## api/config/Database.php
* State database config
* ```
  protected $db_host = "localhost";
  protected $db_name = "test_2023";
  protected $db_user = "root";
  protected $db_password = "";
  protected $db_charset = "utf8";
  ```

## api/Controller/offline.php
* ### User Model
  * initial model `User(DB_TABLE)`
    * [`DB_TABLE`](#dbtable) @api/config/conf.php
  * check db status value `load_status`
    * `false` value if failed to init db
  * check file exist `checkExistSqlStatement($_POST, $validate_columns, $validate_case)`
    * `$validate_columns` check the exist column(s)
      * eg. `$validate_columns = ['mobile', 'email'];`
    * `$validate_case` check the exist condition
      * only available for more than one `validate_columns`
      * eg. `$validate_case = 'and';`
    * return `true` if record exist
    * return `false` otherwise
  * insert into db `insertUser($_POST)`
    * please add your customized filename into `$_POST` object before insertUser
      * `$_POST[DB_FILE_COL] = ${filename};`
    * return `true` if data successfully insert into db
    * return `false` otherwise

---
# Filter - Case for filtering user input

## api/Model/Filter.php

* ### error msg for invalid data format by lang `$lang_error`
  * State the error msg base on cases by language
  * Default lang will be `en` 

* ### data validate format `dataValidate()`
  * State the data validate condition by req key
    * some preset checking function provided:
      * `isAlpha()`
      * `isNum()`
      * `isAlnum()`
      * `isEmail()`
      * `isDate()`
  
## api/Controller/offline.php
* ### Filter Model 
  * initial model `Filter($lang)`
    * `$lang` only affect the error msg
  * data validation `dataValidation()`
    * return fail case by [failMsg](#failmsg) if invalid data format
    * return success case by [successMsg](#successmsg) otherwise


---
# Email - Case for send email

## api/config/conf.php

* ### Email information `EMAIL_INFO`

  * ```
    define('EMAIL_INFO', 
          [
              'username' => 'events@videinsightevent.com',
              'password' => 'Hello123Hello123',
              'sender_name' => 'videinsight',
              'sender_email' => 'events@videinsightevent.com',
              'smtp_host' => 'smtp.gmail.com',
              'smtp_port' => 465,
          ]
    );
    ```
  *   Account `username`
  *   Password `password`
  *   Sender Name `sender_name`
  *   Sender Email `sender_email`
  *   SMTP Host `smtp_host`
  *   SMTP Port `smtp_port`

## api/Model/Email.php
* ### Email layout
  * State the email layout in your selected `$email_type`

## api/Controller/offline.php
* State the email content details
  * adjustable email template `$email_type`
    * eg. `$email_type = 'default';`
  * email data `$email_data` 
    * customise email data: 
      * receiver
      * message
      * link
    * eg.
      ```
      $email_data = new stdClass();
      $email_data->email = 'chris.yip@videinsight.asia'; // Email of receiver
      $email_data->msg  = 'test email message'; // If no tmeplate, only msg
      $email_data->link  = 'https://videinsight.asia'; // If template need moe than 1 custom values e.g, 's1'
      ```
* initial model `Email($email_type, $email_data)`
* send email `sendEmail()`
  * return `array( ['status' => 'fail'] )` if failed in send email

---
# SMS - Case for send SMS

## api/config/conf.php

* ### SMS information `SMS_INFO`
  * ```
    define('SMS_INFO', 
          [
              'sender_id' => '+8526226495103709', // SMS sender number e.g +8526226495103709
              'sender_title' => 'videinsightevent',  // Campaign name, will not show in SMS, for correlation id use, characters only
          ]
    );
    ```
  * Sender id `sender_id`
  * Sender Name `sender_title`

## api/Controller/offline.php
* State the sms info
  * receiver country code `$sms_cc`
    * eg. `$sms_cc = '852';`
  * reciver phone no `$sms_mobile`
    * eg. `$sms_mobile = '67162829';`
  * sent msg `$sms_data` (Object)
    * eg. `$sms_data->msg  = 'https://videinsight.asia';`
* initial model `Sms($sms_cc, $sms_mobile, $sms_data)`
* send SMS `sendSMS()`
  * return `false` if failed to send sms
  * return `true` otherwise

---
# ShortLink - Case for shorten link

## api/Controller/offline.php
* State the requirement of shorten link `https://t.ly/`
  * original link `$long_url`
    * eg. `$long_url = 'https://videinsight.asia';`
  * shorten link data `$tly_data` (Object)
    * shorten link `$id`
      * customise shorten link ending
      * generate a link in `https://t.ly/{id}/` format
      * please leave it empty if no customised link needed
      * eg. `$bitly_data->id = 't4Gh';`
    * qr code `qr_code`
      * needs of qr code
      * return qr code of shorten link
      * eg. `$bitly_data->qr_code = false;`
    * description `desc`
      * Campaign name (internal use only)
      * eg. `$bitly_data->desc = 'videinsightevent';`
  * initial model `ShortLink($long_url, $bitly_data)`
  * gen link anyway `link()`
    * return the original link if failed to generate the shorten link
    * eg. `$shorten_result = $short_link_model->link();`
  * gen link by case `shorten()`
    * eg. `$shorten_result = $short_link_model->shorten();`
    * return fail case by [failMsg](#failmsg) if failed in generate link
    * return success case by [successMsg](#successmsg) 
      * generated link will be located at [`resContent($shorten_result)['link']`](#rescontent)
      * at qr code case, the generated qr code info will be located at [`resContent($shorten_result)['qr_code']`](#rescontent)


---
# Basic Function
## <a id="failmsg"></a>failMsg()
* return array of `$msg` with fail status
    * ```
      [
          'status' => "fail",
          'content' => $msg
      ];
      ```
## <a id="successmsg"></a>successMsg()
* return array of `$msg` with success status
    * ```
      [
          'status' => "success",
          'content' => $msg
      ];
      ```
## testMsgT()
* echo test message starts with `--`
* message will be hidden in default
* location @ `api/Tool/basic.php`

## <a id="resresult"></a>resResult()
* return the boolean value of `status` whether it equals to `success` case in array message
  * ```
    $result = [
      'status' => "success",
      'content' => "success"
    ];

    $check_result = resResult($result); // true
    ```
  * ```
    $result = [
      'status' => "fail",
      'content' => "Invalid id."
    ];

    $check_result = resResult($result); // false
    ```

## <a id="rescontent"></a>resContent()
* return the value of `content` in array message
  * ```
    $result = [
      'status' => "success",
      'content' => "success"
    ];

    $check_content = resContent($result); // "success"
    ```
  * ```
    $result = [
      'status' => "fail",
      'content' => "Invalid id."
    ];

    $check_result = resContent($result); // "Invalid id."
    ```
---
# api document
> template file only, please edit on actual case
* edit endpoint and case(s)
* state the api request body and the return cases