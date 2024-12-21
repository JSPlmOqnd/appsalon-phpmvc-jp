<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
  public static function login(Router $router) {

    $alertas = [];
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $auth = new Usuario($_POST);

      $alertas = $auth->validarLogin();
      
      if (empty($alertas)) {
        // Comprobar si existe el usuatio
        $usuario = Usuario::where('email', $auth->email);
        // depurar($auth);
        if ($usuario) {
          // Verificar el password
          if($usuario->comprobarPasswordVerificado($auth->password)) {
            // Autenticar al ususario
            session_start();

            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;
            
            // Redireccionamiento

            if ($usuario->admin === "1") {

              $_SESSION['admin'] = $usuario->admin ?? null;
              
              header('Location: /admin');
            } else {
              header('Location: /cita');
            }

          };
        } else {
          Usuario::setAlerta('error', "Usuario No encontrado");
        }
      }
    }

    $alertas = Usuario::getAlertas();

    $router->render('auth/login', [
      'alertas' => $alertas
    ]);
    
  }

  public static function logout() {
    session_start();
    // depurar($_SESSION);
    
    $_SESSION = [];
    header('Location: /');
  }

  public static function olvide(Router $router) {

    // Alertas vacias
    $alertas = [];
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {

      $auth = new Usuario($_POST);
      $alertas = $auth->validarEmail();
      // Revisamos que alerta esté vacío
      if (empty($alertas)) {
        // Verificar que el usuario no este previamente registrado
        $usuario = Usuario::where('email', $auth->email);

        if($usuario && $usuario->confirmado === "1") {

          // Generar un Token
          $usuario->crearToken();
          $usuario->guardar();

          // Enviar el email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarInstrucciones();

          Usuario::setAlerta('exito', 'Revisa tu email');

        } else {
          Usuario::setAlerta('error', 'Usuario no existe o no esta confirmado');
        }
      }
    }

    $alertas = Usuario::getAlertas();

    $router->render('auth/olvide-password', [
      'alertas' => $alertas
    ]);
  }

  public static function recuperar(Router $router) {
    $alertas = [];
    $error = false;

    $token = s($_GET['token']);

    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      Usuario::setAlerta('error', 'Token No Válido');
      $error = true;
    } 
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      
      $password = new Usuario($_POST);
      $alertas = $password->validarPassword();
      // Revisamos que alerta esté vacío
      if (empty($alertas)) {
        // Verificar que el usuario no este previamente registrado
        
          $usuario->password = null;
          
          $usuario->password = $password->password;
          $usuario->hashPassword();
          $usuario->token = '';
          $usuario->guardar();
          $resultado = $usuario->guardar();

          // depurar($email);

          if ($resultado) { 
            header('Location: /');
          }
          /* depurar($usuario);
          // Enviar el Email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          $email->enviarConfirmacion(); */

        }
      }
  
    $alertas = Usuario::getAlertas();
    
    $router->render('auth/recuperar-password', [
      'alertas' => $alertas,
      'error' => $error

    ]);
  }

  public static function crear(Router $router) {

    $usuario = new Usuario;

    // Alertas vacias
    $alertas = [];
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      
      $usuario->sincronizar($_POST);
      $alertas = $usuario->validarNuevaCuenta();

      // Revisamos que alerta esté vacío
      if (empty($alertas)) {
        // Verificar que el usuario no este previamente registrado
        $resultado = $usuario->existeUsuario();

        if($resultado->num_rows) {
          $alertas = Usuario::getAlertas();
        } else {
          // Hashear el password
          $usuario->hashPassword();

          // Generar un Token único
          $usuario->crearToken();

          // Enviar el Email
          $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
          $email->enviarConfirmacion();

          // crear el usuario
          $resultado = $usuario->guardar();

          // depurar($email);

          if ($resultado) { 
            header('Location: /mensaje');
          }
        }
      }

    }
    $router->render('auth/crear-cuenta', [
      'usuario' => $usuario,
      'alertas' => $alertas
    ]);
  }

  public static function mensaje(Router $router) {
  
    $router->render('auth/mensaje');
  }

  public static function confirmar(Router $router) {

    $alertas = [];

    $token = s($_GET['token']);

    $usuario = Usuario::where('token', $token);

    if (empty($usuario)) {
      // Mostrar mensaje de error
      Usuario::setAlerta('error', 'Token no válido');
    } else {
      // Modificar a usuario confirmado

      $usuario->confirmado = '1';
      $usuario->token = '';
      $usuario->guardar();
      Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
    }
    
  
    $alertas = Usuario::getAlertas();
    $router->render('auth/confirmar-cuenta', [
      'alertas' => $alertas
    ]);
  }

}