<?php 
ob_start();
require("./config/include.php");

$conexion->SeleccionBD(BASEDATOS);

$conexion->SetearAdmiGeneral(ADMISITE);

$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo->VerificarBloqueo($conexion);

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);
$msg = array();
$msg['IsSucceed'] = false;
$datos = $_POST;
$conexion->ManejoTransacciones("B");

{
	$msg['Msg'] = "Error al procesar";
	echo json_encode($msg);
	ob_end_flush();
	die();
}
$oObjeto = new cPlanJurisdicciones($conexion,"");

switch($datos['accion'])
{
	case 1:
		if($oObjeto->Insertar($datos,$codigoinsertado))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha agregado correctamente a las ".date("H").":".date("i")."Hs"; 
			$msg['planjurisdiccioncod'] = $codigoinsertado; 
			$msg['header'] = "plan_jurisdicciones_am.php?planjurisdiccioncod=".$codigoinsertado; 
		}
	break;
	case 2:
		if($oObjeto->Modificar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha modificado correctamente a las ".date("H").":".date("i")."Hs"; 
		}
	break;
	case 3:
		if($oObjeto->Eliminar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha eliminado correctamente a las ".date("H").":".date("i")."Hs"; 
		}
	break;
	case 4:
		if($oObjeto->DesActivar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha desactivado correctamente a las ".date("H").":".date("i")."Hs"; 
		}
	break;
	case 5:
		if($oObjeto->Activar($datos))
		{
			$msg['IsSucceed'] = true;
			$msg['Msg'] = "Se ha activado correctamente a las ".date("H").":".date("i")."Hs"; 
		}
	break;
}


	$conexion->ManejoTransacciones("C");
else
{
	$msg['Msg'] = utf8_encode(ob_get_contents()); 
	$conexion->ManejoTransacciones("R");
}
ob_clean();
echo json_encode($msg);
ob_end_flush();
?>