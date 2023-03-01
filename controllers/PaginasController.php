<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use PHPMailer\PHPMailer\PHPMailer;

class PaginasController {
  public static function index (Router $router){

    $propiedades = Propiedad::get(3);
    $inicio = true;

    $router->render('paginas/index',  [
      'propiedades' => $propiedades,
      'inicio' => $inicio
    ]);
  }

  public static function nosotros (Router $router){

    $router->render('paginas/nosotros');
  }

  public static function propiedades (Router $router){

    $propiedades = Propiedad::all();
    $router->render('paginas/propiedades', [
      'propiedades' => $propiedades
    ]);
  }

  public static function propiedad (Router $router){
    $id = validarORedireccionar('/propiedades');
    $propiedad = Propiedad::find($id);

    $router->render('paginas/propiedad', [
      'propiedad' => $propiedad
    ]);
  }

  public static function blog (Router $router){

    $router->render('paginas/blog');
  }

  public static function entrada (Router $router){

    $router->render('paginas/entrada');
  }

  public static function contacto (Router $router){

    $mensaje = null;

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

      $respuestas = $_POST['contacto'];
      // debuguear($respuestas);
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->Host = 'sandbox.smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      $mail->Port = 2525;
      $mail->Username = 'a665f82c52d4a9';
      $mail->Password = '82ec948468a1ca';
      $mail->SMTPSecure = 'tls';

      $mail->setFrom('admin@bienesraices.com',$respuestas['nombre']);
      $mail->addAddress('admin@bienesraices.com', 'BienesRaices.com');
      $mail->Subject = 'Tienes un Nuevo Email';
            // Set HTML 
      $mail->isHTML(TRUE);
      $mail->CharSet = 'UTF-8'; 

      $contenido = '<html>';
      $contenido .= "<p><strong>Has Recibido un email:</strong></p>";
      $contenido .= "<p>Nombre: " . $respuestas['nombre'] . "</p>";
      $contenido .= "<p>Mensaje: " . $respuestas['mensaje'] . "</p>";
      $contenido .= "<p>Vende o Compra: " . $respuestas['tipo'] . "</p>";
      $contenido .= "<p>Presupuesto o Precio: $" . $respuestas['precio'] . "</p>";

      if($respuestas['contacto'] === 'telefono') {
      $contenido .= "<p>Eligió ser Contactado por Teléfono:</p>";
      $contenido .= "<p>Su teléfono es: " .  $respuestas['telefono'] ." </p>";
      $contenido .= "<p>En la Fecha y hora: " . $respuestas['fecha'] . " - " . $respuestas['hora']  . " Horas</p>";
      } else {
        $contenido .= "<p>Eligio ser Contactado por Email:</p>";
        $contenido .= "<p>Su Email  es: " .  $respuestas['email'] ." </p>";
      }

      $mail->Body = $contenido;
      $mail->AltBody = "Tetxo alternativo";

      if(!$mail->send()){
        $mensaje = 'Hubo un Error... intente de nuevo';
      } else {
        $mensaje = 'Email enviado Correctamente';
      }
    }

    $router->render('paginas/contacto', [
      'mensaje' => $mensaje
    ]);
  }
}