<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct(string $email, string $nombre, string $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom('cuentas@appsalon.com');
        $phpmailer->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $phpmailer->Subject = 'Confirma tu cuenta';

        //setHtm
        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido = "<p><strong>Hola " . $this->nombre . "</strong>. Has creado tu cuenta en AppSalon, solo debes
         confirmarla presionando en el siguiente enlace </p>";
        $contenido .= "<p>Pesiona aquí <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>.Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        $phpmailer->Body = $contenido;

        $phpmailer->send();
    }

    public function enviarInstrucciones()
    {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = $_ENV['EMAIL_HOST'];
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $_ENV['EMAIL_PORT'];
        $phpmailer->Username = $_ENV['EMAIL_USER'];
        $phpmailer->Password = $_ENV['EMAIL_PASS'];

        $phpmailer->setFrom('cuentas@appsalon.com');
        $phpmailer->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $phpmailer->Subject = 'Reestablece Tu Password';

        //setHtm
        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido = "<p><strong>Hola " . $this->nombre . "</strong>. Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Pesiona aquí <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>.Reestablecer tu password</a> </p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";
        $phpmailer->Body = $contenido;

        $phpmailer->send();
    }
}
