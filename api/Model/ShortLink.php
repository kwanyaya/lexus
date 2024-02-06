<?php

    class ShortLink {
        private $api_token = 'CUS0dAz2oPFqe57c9rBao9OoT7CTN559941SAzeSqL7zD5sIxQIycn8M5B85';
        public $long_url;
        public $data;

        public function __construct($url, $data){
            $this->long_url = $url;
            $this->data = $data;
            if(!$data->qr_code){
                $this->data->qr_code = 'false';
            } else{
                $this->data->qr_code = 'true';
            }
        }

        public function returnMsg($s = 'fail', $c = ''){
            return 
                array(
                    'status' => $s,
                    'content' =>  $c
                );
        }

        public function shorten(){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://t.ly/api/v1/link/shorten?api_token='.$this->api_token,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "long_url": "'.$this->long_url.'",
                    "short_id": "'.$this->data->id.'",
                    "include_qr_code": '.$this->data->qr_code.',
                    "description": "'.$this->data->desc.'",
                    "public_stats": false
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            // var_dump($response);

            if(!isset($response->long_url)){
                return $this->returnMsg('fail', $response->message);
            } else if($response->long_url == $this->long_url){
                if($this->data->qr_code == 'false'){
                    return $this->returnMsg('success', array(
                        'link' => $response->short_url
                    ));
                }

                return $this->returnMsg('success', array(
                    'link' => $response->short_url,
                    'qr_code' => $response->qr_code
                ));
            } 
        }

        public function link(){
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://t.ly/api/v1/link/shorten?api_token='.$this->api_token,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "long_url": "'.$this->long_url.'",
                    "short_id": "'.$this->data->id.'",
                    "include_qr_code": '.$this->data->qr_code.',
                    "description": "'.$this->data->desc.'",
                    "public_stats": false
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response);
            // var_dump($response);

            if(!isset($response->short_url)){
                return $this->long_url;
            } else if($response->long_url == $this->long_url){
                return $response->short_url;
            } 
        }
    }


    