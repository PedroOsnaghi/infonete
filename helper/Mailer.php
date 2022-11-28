<?php

class Mailer
{
    private $phpMailer;

    public function __construct($phpMailer, $email_user, $email_pass, $smtp_host, $smtp_port)
    {

        $this->phpMailer = $phpMailer;
        $this->phpMailer->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
        $this->phpMailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $this->phpMailer->SMTPOptions = array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true));
        $this->phpMailer->Host = $smtp_host;
        $this->phpMailer->Port = $smtp_port;
        $this->phpMailer->Username = $email_user;
        $this->phpMailer->Password = $email_pass;
        $this->phpMailer->isSMTP();
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->CharSet = 'UTF-8';
        $this->phpMailer->setFrom($email_user, 'Infonete');


    }

    public function sendEmailVerification($email, $hash)
    {
        try {

            $this->phpMailer->addAddress($email);

            //Content
            $this->phpMailer->isHTML(true);                                  //Set email format to HTML
            $this->phpMailer->Subject = 'Infonete - Activación de cuenta';
            $this->phpMailer->Body    = '<h1>Gracias por unirte a Infonete</h1>
                                    <p>Para terminar el proceso de registro, deberas activar tu cuenta, haciendo clic en el siguiente enlace:</p>
                                     <br>
                                     <a href="http://localhost/infonete/Usuario/Activate?email='. $email .'&hash='. $hash .'">http://localhost/infonete/Usuario/Activate?email='. $email .'&hash='. $hash .'</a>';

            $this->phpMailer->send();

            return true;
        }catch (Exception $e)
        {
            echo "Message could not be sent. Mailer Error: {$this->phpMailer->ErrorInfo}";
            false;
        }



    }

}