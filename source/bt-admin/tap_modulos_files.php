<? 
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


?>
<link href="css/elfinder.min.css" rel="stylesheet" title="style" media="all" />
<link href="css/theme.css" rel="stylesheet" title="style" media="all" />

<script type="text/javascript" src="js/elfinder.min.js"></script>
<script type="text/javascript" src="js/elfinder.es.js" charset="utf-8"></script>

<script type="text/javascript">

var mytoolbar = [
		['back', 'forward'],
		['upload'],
		['open', 'download', 'getfile'],
		['quicklook'],
		['copy', 'cut', 'paste'],
		['rm'],
		['rename', 'edit'],
		['view']
	];
	
	$().ready(function() {
		var elf = $('#AdminFiles').elfinder({
			url : 'tap_modulos_files_connect.php',  // connector URL (REQUIRED)
			uiOptions : {toolbar : mytoolbar},
			"params"    : {
				"uplMaxSize" : "1M"
			},
			lang: 'es'            // language (OPTIONAL)
		}).elfinder('instance');
});

function Seleccionar()
{
	$("#archnom").focus();
	$("#archnom").select();
}
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h1><i class="fa fa-table" aria-hidden="true"></i>&nbsp;M&oacute;dulos / Administrador de archivos</h1>
    <p class="lead">Desde aqu&iacute; podr&aacute; modificar los archivos de los formularios de carga y html (generado) de cada m&oacute;dulo</p>
</div>

<div id="AdminFiles"></div>
<div class="clearboth">&nbsp;</div>

<div class="menuAcciones accionespagina">
    <a class="btn btn-default" href="tap_modulos.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Volver</a>
</div>

<div class="clearboth">&nbsp;</div>


<?
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>