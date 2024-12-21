<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administración de Servicios</p>

<?php 
  $script = "
    <script src='build/js/confeliminar.js'></script>
  ";
?>

<?php 
  @include_once __DIR__ . '/../templates/barra.php';
?>

 <ul class="servicios">
 <?php foreach($servicios as $servicio) { ?>
  <li>
    <p>Nombre: <span><?php echo $servicio->nombre; ?></p>
    <p>Precio: <span>$<?php echo $servicio->precio; ?></p>

    <div class="acciones">
      <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a>

      <form action="/servicios/eliminar" method="POST">
        <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">

        <input type="submit" value="Borrar" class="boton-eliminar" onclick="return confeliminar('¿Desea Borrar el Servicio?')">
      </form>
    </div>
  </li>
  <?php } ?>
 </ul>