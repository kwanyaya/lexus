<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require __DIR__ . '/../vendor/PHPMailer/src/Exception.php';
    require __DIR__ . '/../vendor/PHPMailer/src/PHPMailer.php';
    require __DIR__ . '/../vendor/PHPMailer/src/SMTP.php';
    // require __DIR__ . '/../template/mailcontent.php';


    class Email {

        private $email_info = EMAIL_INFO;

        private $body;
        private $altBody = "To view the message, please use an HTML compatible email viewer!";

        public $type;
        public $data;
        public $receive_email;
        public $subject;

        public function __construct($type = null, $data){
            $this->type = $type;
            $this->data = $data;
            $this->receive_email = $data->email;
        }

        public function formBody(){
            $temp_body='';
            $sample_str;
            $real_str;
            switch ($this->type) {
                case 's1': // Dynamics msg with static template DEMO
                    $temp_body = $this->body_t1;
                    $sample_str = array("data_LINK_"); // Replace "data_LINK_" with custom value in email body
                    $real_str   = array($this->data->link); 
                    $this->subject = 'Subject s1';
                    break;
                default:   // Dynamics msg only
                    $temp_body = $this->body_dyn;
                    $sample_str = array("data_MSG_");
                    $real_str   = array($this->data->msg);
                    $this->subject = 'Subject default';    
            }
            $temp_body = str_replace($sample_str, $real_str, $temp_body);
            $this->body = $temp_body;
        }

        public function debugEmailBody(){
            $this->formBody();
            return $this->body;
        }

        public function sendEmail(){
            $mail = new PHPMailer(true);
	        try{
                $this->formBody();
                $mail -> SMTPDebug = 0;
                // $mail -> SMTPDebug = 2; // For debug purposes
                $mail -> isSMTP();
                $mail-> Host = $this->email_info['smtp_host'];  
                $mail -> SMTPAuth = True;
                $mail->Username = $this->email_info['username']; 
                $mail->Password = $this->email_info['password'];
                $mail-> SMTPSecure = 'ssl';
                $mail -> Port = $this->email_info['smtp_port'];
                $mail-> CharSet = "UTF-8"; 
                $mail->From = $this->email_info['sender_email'];
                $mail-> FromName = $this->email_info['sender_name'];
                $mail -> addAddress($this->receive_email);
                // $mail -> addAddress('chris.yip@videinsight.asia'); // For demo purposes
                $mail -> isHTML(true);
                $mail -> Subject = $this->subject;
                // $mail->AddEmbeddedImage('./images/logo.png', 'logo'); // For embeddeing image
             
                $mail -> Body = $this->body;
                $mail -> AltBody = $this->altBody;
                $mail-> SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
                $mail -> send();

                return $this->returnMsg('success', '');
            } catch(Exception $e){
                return $this->returnMsg('fail', '');
            }
        }

        public function returnMsg($s = 'fail', $c = ''){
            return 
                array(
                    'status' => $s,
                    'content' =>  $c
                );
        }

        private $body_dyn = 'data_MSG_';

        private $body_t1=' 
        <div id="container" style="width:100%; margin:0 auto; text-align:left; font-size1:16px; font-family:arial;">
            <table align="center" style="width:700px; border:none; border-collapse: collapse;text-align:left;background-color:white;">

                <tr align="center" style="text-align:center;">
                    <td style="border:none;padding:5px;width:100%;">
                        <div style="width:25%;display:flex;margin:0 auto;">   
                            <!-- <img width="150" style="display:block;margin:0 auto;" src="cid:logo" alt=""> -->
                        </div>
                    </td>
                </tr>

                <tr align="center" style="text-align:center;">
                    <td style="border:none;padding: 5px; width:100%;">
                        <br/>
                        <br/>
                        <div style="width: 95%;margin:0 auto; text-align: center;font-size:16px;">
                            <div>
                                <span>
                                Your link is data_LINK_
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr align="center" style="text-align:center;">
                    <td style="display: flex;align-items: center;align-content: center;border:none;padding: 5px; width:100%;">
                        <br/>
                        <div style="width: 100%;margin:0 auto; text-align: center;font-size:16px;">
                            <div style="width:100%;margin:0 auto;">
                                <hr color="#1A2634">
                            </div>
                        </div>
                    </td>
                </tr>
                
            </table>
        </div>';

    
}




?>