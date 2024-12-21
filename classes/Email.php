<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

  public $email;
  public $nombre;
  public $token;

  public function __construct($email, $nombre, $token)
  {
    $this->email = $email;
    $this->nombre = $nombre;
    $this->token = $token;
  }

  public function enviarConfirmacion() {

    // Crear el objetode email
    // $mail = new PHPMailer();

    // Looking to send emails in production? Check out our Email API/SMTP product!
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Port = $_ENV['EMAIL_PORT'];
    $mail->Username = $_ENV['EMAIL_USER'];
    $mail->Password = $_ENV['EMAIL_PASS'];

    //Recipients
    $mail->setFrom('from@example.com');
    $mail->addAddress('joe@example.net', 'Appsalon.com');     //Add a recipient
    /* $mail->addAddress('ellen@example.com');               //Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com'); */

    //Attachments
    /* $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
 */
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    // depurar($mail);
    $mail->CharSet = 'UTF-8';                                  //Set email format to HTML
    $mail->Subject = 'Confirma tu cuenta';

    $contenido = "<html>";
    $contenido.= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en AppSalon, solo debes confirmarla presionando el siguiente enlace</p>";
    $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
    $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
    $contenido .= "</html>";
    
    $mail->Body = $contenido;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';
  }

  public function enviarInstrucciones() {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Port = $_ENV['EMAIL_PORT'];
    $mail->Username = $_ENV['EMAIL_USER'];
    $mail->Password = $_ENV['EMAIL_PASS'];

    //Recipients
    $mail->setFrom('from@example.com');
    $mail->addAddress('joe@example.net', 'Appsalon.com');     //Add a recipient
    //Attachments
   /*  $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
 */
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    // depurar($mail);
    $mail->CharSet = 'UTF-8';                                  //Set email format to HTML
    $mail->Subject = 'Restablece tu password';

    $contenido = "<html>";
    $contenido.= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restableecer tu password. sigue el siguiente enlace para hacerlo.</p>";
    $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Reestablecer Password</a> </p>";
    $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
    $contenido .= "</html>";
    
    $mail->Body = $contenido;
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    // echo 'Message has been sent';

  }

}
