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


//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

?>

<script type="text/javascript" src="modulos/tap_plantillas/js/tap_plantillas_html.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h1><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Plantillas HTML</h1>
</div>

<div class="form">
<form action="tap_plantillas_html.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
    <div class="form-group">
       <label>Descripci&oacute;n:</label>
       <input name="planthtmldesc" id="planthtmldesc" class="form-control input-md" type="text" maxlength="100" size="60" value="" />
       <div class="clear fixalto">&nbsp;</div>
    </div>
</form>
 </div>
<div class="clearboth aire_vertical">&nbsp;</div>

<div class="row">
    <div class="col-md-8">
        <a class="btn btn-default" href="javascript:void(0)" onclick="gridReload()" title="Buscar"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</a>
        <a class="btn btn-default" href="javascript:void(0)" onclick="Resetear()" title="Limpiar Datos"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Limpiar</a>                
    </div>
    <div class="col-md-4">
        <div class="text-right"> 
            <a class="btn btn-success" href="tap_plantillas_html_am.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Crear nueva plantilla HTML</a>
        </div>
    </div>
</div>	   	

<div class="clearboth aire_vertical">&nbsp;</div>
<div id="LstPlantillas" style="width:100%;">
    <table id="ListarPlantillas"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
$oEncabezados->PieMenuEmergente();
?>