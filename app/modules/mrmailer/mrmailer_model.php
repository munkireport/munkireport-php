<?php

/**
 * MRMailer class
 *
 * This is the central place to store events from
 * a client.
 *
 * @package munkireport
 * @author Tuxudo
 **/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mrmailer_model extends \Model
{
    /**
     * Send an email message
     *
     * @param string $type message type
     **/
    public function sendemail($serial, $subject, $message, $linked, $name, $tags)
    {
        $email_default = array(
            "skip_serials"=>array(''),
            "skip_subjects"=>array(''),
            "debug"=>0,
            "use_html"=>true,
            "use_smtp"=>true,
            "locale"=>'en',
            "enabled"=>false,
        );
        
        // Get email settings from config.php
        $email_settings = conf('email')+$email_default;
                
        // Check if emails are enabled and tags do not contain "noemail" and serial number is not in skip_serials config option and subject is not in skip_subjects config option
        if ($email_settings["enabled"] && ! in_array("noemail", $tags) && ! in_array($serial, $email_settings["skip_serials"]) && ! in_array($subject, $email_settings["skip_subjects"])) {
            
            // Load PHPMailer helper
            require_once(dirname(__FILE__).'/lib/PHPMailer/src/Exception.php');
            require_once(dirname(__FILE__).'/lib/PHPMailer/src/PHPMailer.php');
            require_once(dirname(__FILE__).'/lib/PHPMailer/src/SMTP.php');
            
            $mail = new PHPMailer(true); // Passing `true` enables exceptions
            try {
                
                // Set debug level
                $mail->SMTPDebug = $email_settings["debug"];
                
                // If using SMTP, set up config items
                if ($email_settings["use_smtp"]){
                    $mail->isSMTP();
                    $mail->Host = $email_settings["smtp_host"];
                    $mail->SMTPAuth = $email_settings["smtp_auth"];  
                    $mail->Username = $email_settings["smtp_username"];
                    $mail->Password = $email_settings["smtp_password"];
                    $mail->SMTPSecure = $email_settings["smtp_secure"];
                    $mail->Port = $email_settings["smtp_port"];                       
                }

                // Recipients
                foreach ($email_settings["to"] as $to_address => $to_name) {
                    $mail->addAddress($to_address, $to_name);
                }
                // From address
                foreach ($email_settings["from"] as $from_address => $from_name) {
                    $mail->setFrom($from_address, $from_name);
                }
                
                if(array_key_exists("replyto", $email_settings)) {
                // If reply to is set, include it
                    foreach ($email_settings["replyto"] as $reply_address => $reply_name) {
                        $mail->addReplyTo($reply_address, $reply_name);
                    }
                }
                
                if(array_key_exists("cc", $email_settings)) {
                // If CC is set, include it
                    foreach ($email_settings["cc"] as $cc_address => $cc_name) {
                        $mail->addCC($cc_address, $cc_name);
                    }
                }
                
                if(array_key_exists("bcc", $email_settings)) {
                // If BCC is set, include it
                    foreach ($email_settings["bcc"] as $bcc_address => $bcc_name) {
                        $mail->addBCC($bcc_address, $bcc_name);
                    }
                }
                
                // Set local used by PHPMailer
                $mail->setLanguage = $email_settings["locale"];

                if (isset($linked)){
                // If there is a client tab to link to, add it to the machine's URL
                    $machine_url = conf('webhost').conf('subdirectory').conf('index_page').'/clients/detail/'.$serial.'#tab_'.$linked;
                } else {
                // Else, just set the machine's URL
                    $machine_url = conf('webhost').conf('subdirectory').conf('index_page').'/clients/detail/'.$serial;
                }
                                                
                // Fill message content
                $mail->isHTML($email_settings["use_html"]);
                $mail->Subject = $name.' - '.$subject;
                $mail->AltBody = $name.' '.str_replace("<br />","\r\n",$message);
                
                // Mail HTML derived from OS X Server's alert emails
                $mail->Body    = '<html>
                    <head>
                        <style type="text/css">
                            @media only screen and (device-width:320px){
                                /* adjust the container div to work within the 320px width of MobileMail */
                                .container{
                                    width:320px !important;
                                }

                                .body_text p:last-child{
                                    margin-bottom:0 !important;
                                }
                            }
                        </style>
                    </head>
                    <body style="text-size-adjust:auto; -webkit-text-size-adjust:auto;">
                        <!-- Content -->
                        <!-- The email module for MunkiReport was made by Tuxudo -->

                        <div class="container" style="text-size-adjust:auto; -webkit-text-size-adjust:auto; font-family: "Helvetica Neue", Helvetica, Arial, Geneva, Verdana, sans-serif;">
                            <div bgcolor="#f3f3f3" style="text-align:left; margin:0; padding:2px 5px; background: #f3f3f3; border-radius: 4px;">
                                <h1><a href="'.$machine_url.'" style="text-decoration: none; color: #2d2d2d; margin:0; padding:0; font-size:15px;">'.$name.' - '.$serial.' - '.$subject.'</a></h1>
                            </div>

                            <div class="content_body">
                                <div class="body_text" style="padding-left:5px; padding-right:300px; padding-top:20px; padding-bottom:20px; min-height: 200px; min-width: 200px;">
                                    <!-- Description -->
                                    <p style="margin:0; margin-bottom:1em; padding:5px: font-size:12px; line-height:1.4; color:4c4c4c"><a href="'.$machine_url.'">'.$name.'</a> '.$message.'</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <p style="text-align: right; margin: 10px; color: #707070; font-size: 80%;">Message generated and sent by <a href="https://github.com/munkireport/munkireport-php" style="text-decoration: none;color: #707070;">MunkiReport</a> version '.$GLOBALS["version"].'</p>
                    </body>
                </html>';
                
                $mail->send();
//                print_r ("\n@@@@@ Message has been sent! @@@@@\n");

            } catch (Exception $e) {
                // Set local used by PHPMailer
                $mail->setLanguage = $email_settings["locale"];
                print_r ("\n!!!!! Message could not be sent !!!!!\n");
                print_r ("\nMailer Error: ".$mail->ErrorInfo."\n");
            }
        }
    }

} // END Mrmailer class
