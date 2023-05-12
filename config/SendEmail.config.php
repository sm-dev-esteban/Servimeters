<?php

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    private $correo;
    private $config;

    function __construct(){
        include_once('../config/PhpMailer/Exception.php');
        include_once('../config/PhpMailer/PHPMailer.php');
        include_once('../config/PhpMailer/SMTP.php');
        $this->correo = new PHPMailer(true);
        require_once "LoadConfig.config.php";
        $this->config = LoadConfig::getConfig();
    }


    public function sendEmail($to, $cc, $subject, $body){

        try{
            $this->correo->SMTPDebug=0;
            $this->correo->isSMTP();
            $this->correo->Host = $this->config->HOST_EMAIL;
            $this->correo->SMTPAuth = true;
            $this->correo->Username = $this->config->USERNAME_EMAIL;
            $this->correo->Password = $this->config->PASS_EMAIL;
            $this->correo->SMTPSecure = "tls";
            $this->correo->Port = $this->config->PORT_EMAIL;
        
            $this->correo->setFrom($this->config->FROM_EMAIL,"Solicitud de Horas Extra");
            $this->correo->addAddress($to);
            $this->correo->addAddress($this->config->FROM_EMAIL);
            $this->correo->addCC($cc);
            $this->correo->isHTML(true);
            $this->correo->Subject = $subject;
            $this->correo->Body = $body;
            $this->correo->CharSet = "UTF-8";
            // $this->correo->send(); // la funcion envia un correo cada que se ejecuata -- no descomentar

            if(!$this->correo->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $this->correo->ErrorInfo;
            } else {
                echo 'Message has been sent';
            }
            return true;
        }
        catch (Exception $e){
            return $this->correo->ErrorInfo;
        }

    }
}