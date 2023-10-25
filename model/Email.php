<?php

namespace Model;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email
{

    private $Mailer;

    function __construct()
    {
        $this->Mailer = new PHPMailer(true);
    }

    public function sendEmail(array|String $to, String $cc, $subject, $body)
    {
        $address = is_array($to) ? $to : [
            [
                "mail" => $to
            ]
        ];

        $address[] = [
            "name" => "no reaply",
            "mail" => MAIL["FROM"]
        ];

        try {
            $this->Mailer->SMTPDebug = false;
            $this->Mailer->isSMTP();
            $this->Mailer->Host = MAIL["HOST"];
            $this->Mailer->SMTPAuth = true;
            $this->Mailer->Username = MAIL["USERNAME"];
            $this->Mailer->Password = MAIL["PASSWORD"];
            $this->Mailer->SMTPSecure = MAIL["SMTPSECURE"] ?? PHPMailer::ENCRYPTION_SMTPS;
            $this->Mailer->Port = MAIL["PORT"];

            $this->Mailer->setFrom(MAIL["FROM"], "Gestión servimeters");

            foreach ($address as $data) if ($data["mail"] ?? false)
                if ($data["name"] ?? false)
                    $this->Mailer->addAddress($data["mail"], $data["name"]);
                else
                    $this->Mailer->addAddress($data["mail"]);
            $this->Mailer->addAddress("esteban.serna@servimeters.com");

            $this->Mailer->addCC($cc);
            $this->Mailer->isHTML(true);

            $this->Mailer->Subject = $subject;
            $this->Mailer->Body = $body;
            $this->Mailer->CharSet = CHARSET;

            $this->Mailer->send();
        } catch (Exception $e) {
            return [
                "status" => false,
                "error" => $this->Mailer->ErrorInfo,
                "address" => $address
            ];
        } finally {
            return [
                "status" => true,
                "error" => false,
                "address" => $address
            ];
        }
    }
}
