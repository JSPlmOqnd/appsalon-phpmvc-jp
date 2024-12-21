<?php

namespace Model;

class Usuario extends ActiveRecord{ 
  // Base de datos
  protected static $tabla = 'usuarios';
  protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

  public $id; 
  public $nombre; 
  public $apellido; 
  public $email; 
  public $password; 
  public $telefono; 
  public $admin; 
  public $confirmado; 
  public $token;

  public function __construct($args = []) {
    $this->id = $args['id'] ?? null; 
    $this->nombre = $args['nombre'] ?? ''; 
    $this->apellido = $args['apellido'] ?? ''; 
    $this->email = $args['email'] ?? ''; 
    $this->password = $args['password'] ?? ''; 
    $this->telefono = $args['telefono'] ?? ''; 
    $this->admin = $args['admin'] ?? '0'; 
    $this->confirmado = $args['confirmado'] ?? '0'; 
    $this->token = $args['token'] ?? '';
  }

  // Mensajes de validación para la creación de una cuenta
  public function validarNuevaCuenta() {
    if(!$this->nombre) {
      self::$alertas['error'][] = "El Nombre es Obligatorio";
    }

    if(!$this->apellido) {
      self::$alertas['error'][] = "El Apellido es Obligatorio";
    }
    
    if(!$this->email) {
      self::$alertas['error'][] = "El Email es Obligatorio";
    }

    $emailv = filter_var($this->email, FILTER_VALIDATE_EMAIL);
    if(!$emailv) {
      self::$alertas['error'][] = "El Email NO ES correcto";
    }
    
    /* if(!$this->telefono) {
      self::$alertas['error'][] = "El Telefono es Obligatorio";
    } */
    
    if(!$this->password) {
      self::$alertas['error'][] = "El Password es Obligatorio";
    }
    if(strlen($this->password < 6)) {
      self::$alertas['error'][] = "El Password debe tener al menos 6 caracteres";
    }
    
    return self::$alertas; 
  }
  // Mensajes de validación autenticar usuario
  public function validarLogin() {
    
    if(!$this->email) {
      self::$alertas['error'][] = "El Email es Obligatorio";
    }
    
    if(!$this->password) {
      self::$alertas['error'][] = "El Password es Obligatorio";
    }
    
    return self::$alertas; 
  }
  // Validar email
  public function validarEmail() {
    
    if(!$this->email) {
      self::$alertas['error'][] = "El Email es Obligatorio";
    }
    
    return self::$alertas; 
  }
  // Validar password
  public function validarPassword() {
    
    if(!$this->password) {
      self::$alertas['error'][] = "El Password es Obligatorio";
    }    

    if(strlen($this->password) < 6) {
      self::$alertas['error'][] = "El Password debe tener al menos 6 caracteres";
    }

    return self::$alertas; 
  }

  // Revisa si el usuario ya existe
  public function existeUsuario() {
    $query = "SELECT * FROM  " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
    
    $resultado = self::$db->query($query);

    if ($resultado->num_rows) {
      self::$alertas['error'][] = 'El usuario ya está registrado';
    }

    return $resultado;
    // depurar($resultado);
  }

  public function hashPassword() {
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function crearToken() {
    $this->token = uniqid();
  }
  
  public function comprobarPasswordVerificado($password) {
    
    if ($this->password) {
      $resultado = password_verify($password, $this->password);

      if (!$resultado || !$this->confirmado) {
        self::$alertas['error'][] = 'Password incorrecto o tu cuenta no ha sido confirmada';
      } else {        
        return true;
      }
    } else {
      self::$alertas['error'][] = 'Error en datos de password del usuario';
    }

  }
}