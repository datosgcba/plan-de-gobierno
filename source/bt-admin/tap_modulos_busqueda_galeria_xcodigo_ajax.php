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

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);

$oGalerias= new cGalerias($conexion);

header('Content-Type: text/html; charset=iso-8859-1'); 

$datos['galeriacod'] = $_POST['galeriacod'];
if(!$oGalerias->BuscarxCodigo($datos,$resultadogaleria,$numfilas)) {
	$error = true;
}
if ($numfilas>0)
{
	
	$fila = $conexion->ObtenerSiguienteRegistro($resultadogaleria);
	if ($fila["multimediaubic"]!="")
	{
		$img="<img src='".CARPETA_SERVIDOR_MULTIMEDIA."noticias/Thumbs/".$fila["multimediaubic"]."'/>";
		echo $img;
	}
}
?>
