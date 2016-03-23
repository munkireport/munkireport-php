<?php

namespace munkireport\email;

class Email {
    
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }
    
    /**
     * Send email
     *
     * Send email 
     *
     * @param array $email Email properties
     **/
    public function send($email)
    {
        include_once (APP_PATH . '/lib/phpmailer/class.phpmailer.php');
        include_once (APP_PATH . '/lib/phpmailer/class.smtp.php');
        $mail = new \PHPMailer;
        
        // Get from
        list($from_addr, $from_name) = each($this->config['from']);
        
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = $this->config['smtp_host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = $this->config['smtp_auth']; // Enable SMTP authentication
        $mail->Username = $this->config['smtp_username']; // SMTP username
        $mail->Password = $this->config['smtp_password']; // SMTP password
        $mail->SMTPSecure = $this->config['smtp_secure']; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $this->config['smtp_port']; // TCP port to connect to
        $mail->CharSet = "UTF-8";
        $mail->setFrom($from_addr, $from_name);
        
        // Add recipient(s)
        foreach($email['to'] as $to_addr => $to_name)
        {
            $mail->addAddress($to_addr, $to_name);
        }

        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $email['subject'];
        $mail->Body    = $email['content'];

        if( ! $mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            // Update notification object
        }

    }
}