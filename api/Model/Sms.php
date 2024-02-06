<?php

    class Sms {

        public $correlation_id;
        public $schedule_datetime;
        public $send_mobile;
        public $send_data;
        public $send_body;
        public $sender_id = SMS_INFO['sender_id']; // SMS sender number e.g +8526226495103709
        public $sender_title = SMS_INFO['sender_title']; // Will not show in SMS, for correlation id use, characters only

        public function __construct($cc, $mobile, $data){
            $this->correlation_id = $this->sender_title . $mobile; 
            $this->schedule_datetime = $this->getDate();
            $this->send_mobile = '+' . $cc . $mobile;
            $this->send_data = $data->msg;
        }

        public function getDate(){
            date_default_timezone_set('Asia/Hong_Kong');
            // $date = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +5 seconds"));
            $date = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")));
            return $date;
        }

        public function formBody(){
            $temp_body = $this->sms_req_body;
            $sample_str = array("data_CORRELATION_ID_", "data_SCHEDULE_DATETIME_", "data_SEND_MOBILE_", "data_SEND_DATA_", "data_SENDER_ID_"); // Replace "data_LINK_" with custom value in wmal body
            $real_str   = array($this->correlation_id, $this->schedule_datetime, $this->send_mobile, $this->send_data, $this->sender_id); 
            $temp_body = str_replace($sample_str, $real_str, $temp_body);
            $this->send_body = $temp_body;
        }

        public function sendSMS(){
            $this->formBody();
            $sms_api = 'https://api.emma.hk/sms/APIServiceMulti';
            $send_request = $this->send_body;
            
            $headers = array(
                "charset=utf-8",
                "Content-length: " . strlen($send_request),
                "Connection: close",
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $sms_api);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $send_request);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            @$data = curl_exec($ch);
            // echo $data; // Debug use
            if(curl_errno($ch)){ // Error occur while sending request
                return false;
                exit();
            }
            else{
                curl_close($ch);
                return true;
            }
        }
      
        private $sms_req_body = 
        'sendrequest=<?xml version="1.0" encoding="UTF-8"?><sendrequest><correlationid>data_CORRELATION_ID_</correlationid><username>videinsightasia-otp-api</username><password>viao@5851</password><messages><message><scheduledatetime>data_SCHEDULE_DATETIME_</scheduledatetime><phonenumbers>data_SEND_MOBILE_</phonenumbers>'
        .'<content>'
            // .'Thank you for joining us tonight.%0a %0aThis is you testing link%0a data_SEND_DATA_' // For template purpose
            .'data_SEND_DATA_'
        .'</content>'
        .'<senderid>data_SENDER_ID_</senderid></message></messages></sendrequest>';


    }


?>