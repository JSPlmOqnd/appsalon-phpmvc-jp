<h1 class="nombre-pagina">Panel de Administración</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>


<?php 
  @include_once __DIR__ . '/../templates/barra.php';
?>

<h2>Buscar Citas</h2>
<div class="busqueda">
  <form action="" class="formulario">
    <div class="campo">
      <label for="fecha">Fecha</label>
      <input 
        type="date"
        id="fecha"
        name="fecha"
        value="<?php echo $fecha; ?>"
        >
    </div>
  </form>
</div>

<?php
  if (count($citas) === 0) {
    echo "<h2>No hay citas en esta fecha</h2>";
  };
?>

<div id="citas-admin">
  <ul class="citas">
    <?php
      $idCita = 0;
      $totalCita = 0;
      $totalCitas = 0;
      foreach($citas as $cita ) {
          if($idCita !== $cita->id) {
    ?>
      <?php
          if($idCita !== 0) {
          // depurar($idCita);
      ?>

      <p class="total">Total Cita: <span>$ <?php echo $totalCita; ?></span> </p>

      <form action="/api/eliminar" method="POST">
        <input type="hidden" name="id" value="<?php echo $idCita; ?>">
        <input type="submit" class="boton-eliminar" value="Eliminar" onclick="return confeliminar('¿Desea Eliminar la cita?')">
      </form>
      
      <?php
        $totalCitas += $totalCita;
        $totalCita = 0;
        $idCita = $cita->id;
      } else {
        $idCita = $cita->id;
      }// fin del 2do if ?>
      <li>
          <p>ID: <span><?php echo $cita->id; ?></span></p>
          <p>Hora: <span><?php echo $cita->hora; ?></span></p>
          <p>Cliente: <span><?php echo $cita->cliente; ?></span></p>
          <p>Email: <span><?php echo $cita->email; ?></span></p>
          <p>Telefono: <span><?php echo $cita->telefono; ?></span></p>

          <h3>Servicios</h3>

          <?php

            // $idCita = $cita->id;
          } // fin del if ?>
          
          <p class="servicio"><?php echo $cita->servicio . " " . $cita->precio; ?></p>
          <?php $totalCita += $cita->precio ?>
          
      </li>
    <?php } // fin del foreach 
    
          if($totalCita > 0) { 
    ?>
    <p class="total">Total Cita: <span>$ <?php echo $totalCita; ?></span> </p>
    <form action="/api/eliminar" method="POST">
      <input type="hidden" name="id" value="<?php echo $idCita; ?>">
      <input type="submit" class="boton-eliminar" value="Eliminar" onclick="return confeliminar('¿Desea Eliminar la cita?')">
    </form>

    <?php } // fin del foreach 
      $totalCitas += $totalCita;
      if($totalCitas > 0) { 
    ?>
    <p class="total">Total General: <span>$ <?php echo $totalCitas; ?></span> </p>

    <!-- <form action="/api/eliminar" method="POST">
    </form> -->
    <?php } ?>

  </ul>
</div>

<?php
  $script = "
    <script src='build/js/buscador.js'></script>
    <script src='build/js/confeliminar.js'></script>
    ";
?>