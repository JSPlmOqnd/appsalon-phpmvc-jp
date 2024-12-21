<?php

namespace Controllers;

use Model\Cita;
use Model\CitasServicio;
use Model\Servicio;

class APIController {
  public static function Index() {
    $servicios = Servicio::all();
    echo json_encode($servicios);
  }

  public static function guardar() {
    
    // Almacana la Cita y devuelve el ID
    $cita = new Cita($_POST);
    $resultado = $cita->guardar();

    $id = $resultado['id'];

    // Almacana los servicios con el ide de la cita

    $idServicios = explode(',', $_POST['servicios']);

    foreach($idServicios as $idServicio) {
      $args = [
        'citaId' => $id,
        'servicioId' => $idServicio
      ];
      $citaServicio = new CitasServicio($args);
      $citaServicio->guardar();
    };

    echo json_encode(['resultado' => $resultado]);
  }

  public static function eliminar() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'];
      $cita = Cita::find($id);
      $cita->eliminar();
      header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
  }
}