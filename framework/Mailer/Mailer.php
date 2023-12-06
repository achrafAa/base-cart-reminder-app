<?php

namespace Achraf\framework\Mailer;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private PHPMailer $mailer;

    public function __construct(PHPMailer $mailer)
    {
        $this->mailer = $mailer;
        //Server settings

        $this->mailer->SMTPDebug = 2;
        $this->mailer->isSMTP();
        $this->mailer->Host = config('MAIL_HOST') ?? '';
        $this->mailer->SMTPAuth = false;
        $this->mailer->Port = config('MAIL_PORT') ?? 1025;
    }

    public function sendEmail($to, $subject, $body): void
    {
        try {
            //Recipients
            $this->mailer->setFrom(config('MAIL_FROM_ADDRESS'), '');
            $this->mailer->addAddress($to, '');
            //Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->send();
        } catch (Exception $exception) {
            logToFile('error', $exception);
            logToFile('error', 'Message could not be sent. Mailer Error: '.$this->mailer->ErrorInfo);
        }
    }
}
