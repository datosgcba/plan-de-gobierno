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

// ve si el sistema est� bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oTapas= new cEncuestas($conexion);

$_SESSION['msgactualizacion'] = "";
$tapatipocod="";
?>
<link rel="stylesheet" type="text/css" href="modulos/enc_encuestas/css/estilos.css" />
<script type="text/javascript" src="modulos/enc_encuestas/js/enc_encuestas.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Encuestas</h2>
</div>
 
<div class="form">
<form action="enc_encuestas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
    <div class="ancho_10">
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_3">
                <div class="ancho_4">
                    <label>Pregunta:</label>
                </div>
                <div class="ancho_6">
                   <input name="encuestapregunta" id="encuestapregunta" class="full" type="text"  onkeydown="doSearchEncuestas(arguments[0]||event)" maxlength="100" size="60" value="" />
                </div>
            </div>
            
            <div class="ancho_3">&nbsp;</div>

    </div>
    <div class="clear" style="height:1px;">&nbsp;</div>
   	</form>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <ul>
        <li><a class="boton verde" href="javascript:void(0)" onclick="AltaEncuestas()">Crear nueva Encuesta</a></li>
    </ul>
</div>
</div>
<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstEncuestas" style="width:100%;">
    <table id="listarEncuestas"></table>
    <div id="pager2"></div>
</div>
<div class="clear" style="height:1px;">&nbsp;</div>
<div id="Popup"></div>
<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>