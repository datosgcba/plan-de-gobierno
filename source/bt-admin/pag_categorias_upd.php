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

$oPaginasCategorias = new cPaginasCategorias($conexion,"");


header('Content-Type: text/html; charset=iso-8859-1'); 

$_SESSION['msgactualizacion'] = "";
$ret['IsSuccess'] = false;
/*$ret['md5recarga'] = "";
$ret['md5upd'] = "";*/
$conexion->ManejoTransacciones("B");
$error = false;

$_POST=FuncionesPHPLocal::ConvertiraUtf8 ($_POST);

$texto = "";

switch ($_POST['accion'])
{
	case 1:
		if ($oPaginasCategorias->InsertarCategoria($_POST,$catcod))
		{
			$msgactualizacion = utf8_encode("Se ha agregado la categoria de la p�gina correctamente.");
			$ret['IsSuccess'] = true;	
			/*FuncionesPHPLocal::ArmarLinkMD5("clientes_alta.php",array("clientecod"=>$clientecod),$get,$md5);
			FuncionesPHPLocal::ArmarLinkMD5("clientes_upd.php",array("clientecod"=>$clientecod),$getupd,$md5upd);
			$ret['clientecod'] = $clientecod;
			$ret['md5recarga'] = $md5;
			$ret['md5upd'] = $md5upd;*/
		}
		break;
	case 2:
		if ($oPaginasCategorias->ModificarCategoria($_POST))
		{
			$msgactualizacion = utf8_encode("Se ha modificado la categoria de la p�gina correctamente.");
			$ret['IsSuccess'] = true;	
		}
		break;
	case 3:
		if ($oPaginasCategorias->EliminarCategoria($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = utf8_encode("Se ha eliminado la categoria de la p�gina correctamente.");
			
		}
		break;
	case 4:
		if ($oPaginasCategorias->DesActivarCategoria($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha desactivado la categoria correctamente.";
			
		}
		break;

	case 5:
		if ($oPaginasCategorias->ActivarCategoria($_POST))
		{
			$ret['IsSuccess'] = true;
			$msgactualizacion = "Se ha activado la categoria correctamente.";
			
		}
		break;	
	case 6:
		if($oPaginasCategorias->ModificarOrden($_POST))
		{
			$ret['IsSuccess'] = true;	
			$msgactualizacion = utf8_encode("Se ha modificado el orde de la categoria de la p�gina correctamente."); 
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