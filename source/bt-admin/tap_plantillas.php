<?php  
require('./config/include.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
$_SESSION['volver']= "tap_macros.php"; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
?>
<link rel="stylesheet" type="text/css" href="modulos/tap_macros/css/tap_macros.css" />
<script type="text/javascript" src="modulos/tap_plantillas/js/tap_plantillas.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Listado de Plantillas</h2>
</div>
   
<div class="form">
<form action="tap_plantillas.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <div class="row">
        <div class="col-md-12">
            <label for="plantdesc">Descripci&oacute;n:</label>
            <input name="plantdesc" id="plantdesc" class="form-control input-md" type="text" maxlength="100" size="60" value="" />
        </div>
       <div class="clear aire_vertical">&nbsp;</div>
    </div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="row">
    <div class="col-md-8">
        <a class="btn btn-default" href="javascript:void(0)" onclick="gridReload()" title="Buscar"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</a>
        <a class="btn btn-default" href="javascript:void(0)" onclick="Resetear()" title="Limpiar Datos"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Limpiar</a>                
    </div>
    <div class="col-md-4">
        <div class="text-right">
            <a class="btn btn-success" href="javascript:void(0)" onclick="AltaPlantilla()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar Plantilla</a>
        </div>
    </div>
</div>	   	

<div class="clear aire_vertical">&nbsp;</div>

<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstPlantillas" style="width:100%;">
    <table id="ListarPlantillas"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>

<div id="ModalPlantillas" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Plantillas - <span id="TituloPopup"></span></h4>
      </div>
      <div class="modal-body">
        <div id="dataPlantilla" >
            
        </div>
        <div class="clearboth"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<?php  
$oEncabezados->PieMenuEmergente();
?>