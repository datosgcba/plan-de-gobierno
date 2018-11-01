<?php  
ob_start();
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



header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;
$error = false;

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";



$conexion->ManejoTransacciones("B");
switch ($_POST['accion'])
{
	case 1:
		$oGraficosValores = new cGraficosValores($conexion,"");
		if ($oGraficosValores->Actualizar($_POST))
		{
			$msgactualizacion = "Se ha modificado los valores del grafico correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;	
	case 2:
		$oGraficosValores = new cGraficosValoresPorcentajes($conexion,"");
		if ($oGraficosValores->Actualizar($_POST))
		{
			$msgactualizacion = "Se ha modificado los valores del grafico correctamente.";
			$ret['IsSuccess'] = true;	
		}
		break;	
}


if ($ret['IsSuccess'])
{	
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,$msgactualizacion,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$texto));
	$conexion->ManejoTransacciones("C");
}
else
	$conexion->ManejoTransacciones("R");

	
$ret['Msg'] = ob_get_contents();

ob_clean();
echo json_encode($ret); 
ob_end_flush();
?>