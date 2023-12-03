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
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom('cuentas@uptask.com');
        $phpmailer->addAddress('cuentas@uptask.com', 'uptask.com');
        $phpmailer->Subject = 'Conrima tu Cuenta';

        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= <<<HTML
            <p><strong>Hola $this->nombre</strong> Has Creado tu cuenta en UpTask, solo debes confirmarla en el siguiente enlace.</p>
        HTML; 
        $contenido .= "<p>Presiona aquí: <a href= '" . $_ENV['APP_URL'] . "'/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';

        $phpmailer->Body = $contenido;
        
        $resultadoSend = $phpmailer->send();

        if(!$resultadoSend) {
            self::$alertas['error'][] = 'No se ha podido enviar el correo, inténtelo nuevamente';
        }

        return $resultadoSend;
    }

    public function enviarInstrucciones() {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom('cuentas@uptask.com');
        $phpmailer->addAddress('cuentas@uptask.com', 'uptask.com');
        $phpmailer->Subject = 'Reestablece Tu Contraseña';

        $phpmailer->isHTML(TRUE);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= <<<HTML
            <p><strong>Hola $this->nombre</strong> Parece que has olvidadto tu contraseña, sigue el siguiente enlace para recuperarlo.</p>
        HTML; 
        $contenido .= "<p>Presiona aquí: <a href= '" . $_ENV['APP_URL'] . "'/reestablecer?token=" . $this->token . "'>Reestablecer Contraseña</a></p>";
        $contenido .= "<p>Si no has intentado reestablecer tu cuenta, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';

        $phpmailer->Body = $contenido;
        
        $resultadoSend = $phpmailer->send();

        if(!$resultadoSend) {
            self::$alertas['error'][] = 'No se ha podido enviar el correo, inténtelo nuevamente';
        } else {
            self::$alertas['exito'][] = 'Hemos enviado las instrucciones a tu correo Electrónico';
        }


        return $resultadoSend;
    }
}
