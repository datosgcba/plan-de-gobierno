<?php  
include("../config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);

$oEncabezados->setTitle("P&aacute;gina no disponible - Error 404");
$oEncabezados->EncabezadoMenuEmergente();
?>
<header class="jumbotron jumbotron-main jumbotron-small area-header jumbotron-404">
      <div class="jumbotron-overlay">
        <div class="container">
          <div class="area-title col-md-8 col-md-offset-2">
            <div class="status-icon status-icon-no"></div>
            <h1>P&aacute;gina no encontrada</h1>
            <p class="lead">La direcci&oacute;n web no est&aacute; bien escrita o fue dada de baja.</p>
          </div>
        </div>
      </div>
</header>
<?php  
$oEncabezados->PieMenuEmergente();
?>