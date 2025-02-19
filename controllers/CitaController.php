<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class CitaController {
  public static function Index(Router $router) {

    session_start();

    isAuth();

    $router->render('cita/index', [
      'nombre' => $_SESSION['nombre'],
      'id' => $_SESSION['id']
    ]);
  }
}