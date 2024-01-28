<?php 

namespace Classes;

use Model\ActiveRecord;
use PHPMailer\PHPMailer\PHPMailer;

class Email extends ActiveRecord{

    protected $email;
    protected $nombre;
    protected $token;
    
    public function __construct($email, $nombre, $token) {
        
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;

        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom($_ENV['EMAIL_USER'], 'Uptask');
        $phpmailer->addAddress($this->email, $this->nombre);
        $phpmailer->Subject = 'Confirma tu Cuenta';

        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $linkConfirmar = $_ENV['APP_URL'] . '/confirmar?token=' . $this->token;
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola $this->nombre</strong><br>Has Creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href= '" . $linkConfirmar . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no has creado esta cuenta, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';
        $phpmailer->Body = $contenido;

        // $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $phpmailer->AltBody = "Ve al siguiente enlace para activar tu cuenta:  " . $linkConfirmar;

        if($phpmailer->send()){
            echo 'Message has been sent';
            return true;
        }else{
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $phpmailer->ErrorInfo;
            self::$alertas['error'][] = 'No se ha podido enviar el correo, inténtelo nuevamente';
            self::$alertas['error'][] = $phpmailer->ErrorInfo;
            return false;
        }
    }

    public function enviarInstrucciones() {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->SMTPAuth = true;

        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom($_ENV['EMAIL_USER'], 'Uptask');
        $phpmailer->addAddress($this->email, $this->nombre);
        $phpmailer->Subject = 'Reestablece Tu Contraseña';

        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $linkReestablecer = $_ENV['APP_URL'] . '/reestablecer?token=' . $this->token;
        $contenido = '<html>';
        $contenido .= "<p><strong>Hola $this->nombre</strong> <br>Parece que has olvidadto tu contraseña, sigue el siguiente enlace para recuperarla.</p>";
        $contenido .= "<p>Presiona aquí: <a href= '" . $linkReestablecer . "'>Reestablecer Contraseña</a></p>";
        $contenido .= "<p>Si no has intentado reestablecer tu cuenta, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';
        $phpmailer->Body = $contenido;

        // $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $phpmailer->AltBody = "Ve al siguiente enlace: " . $linkReestablecer;

        if($phpmailer->send()){
            // echo 'Message has been sent';
            self::$alertas['exito'][] = 'Hemos enviado las instrucciones a tu correo Electrónico';
            return true;
        }else{
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
            self::$alertas['error'][] = 'No se ha podido enviar el correo, inténtelo nuevamente';
            self::$alertas['error'][] = $phpmailer->ErrorInfo;
            return false;
        }
    }
}
