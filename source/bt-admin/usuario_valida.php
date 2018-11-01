<?php 
ob_start();
require('./config/include.php');
include(DIR_LIBRERIAS."autoload.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session 

$_SESSION['sistema']=SISTEMA;
$_SESSION['mostrarmsgbloqueo'] ="NO";

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$usuarios=new cUsuarios($conexion);

//----------------------------------------------------------------------------------------- 
function ValidarDatosLogin($conexion,&$datosvalidados,$oUsuarios)
{
	$datosbien=true;
	if ($datosbien)
	{
		$ArregloDatos['usuarioemail']=$_POST['usuarioid'];
		$ArregloDatos['usuariopassword']=md5($_POST['usuarioclave']);
		if (!$oUsuarios->BuscarUsuarios ($ArregloDatos,$queryusuario,$numfilas) || $numfilas!=1)
			$datosbien=false;
	}


	if(!$datosbien)
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,'013801',"usuarioid=".$_POST['usuarioid'],$_SESSION['usuariocod']);
		
		ob_end_clean();
		$_SESSION['usuarioiderror'] = $_POST['usuarioid'];
		header("Location:index.php?msg_error=1&usuarioiderror=".$_POST['usuarioid']);
		die();
	}
	else
	{
		$filausuario=$conexion->ObtenerSiguienteRegistro($queryusuario);
		//asignacion de variables de session
		$datosvalidados['usuariocod'] = $filausuario['usuariocod'];
		$datosvalidados['usuarionombre'] = $filausuario['usuarionombre'];
		$datosvalidados['usuarioapellido'] = $filausuario['usuarioapellido'];
		$datosvalidados['avatar'] = $filausuario['imgubic'];


		switch ($filausuario['usuarioestado'])
		{
			case USUARIONUEVO :
			case USUARIOACT :

				$spnombre="sel_roles_xusuariocod";
				$spparam=array("pusuariocod"=>$datosvalidados['usuariocod']);
				if(!$conexion->ejecutarStoredProcedure($spnombre,$spparam,$query,$numfilas,$errno))
					return false;

				$filarol=$conexion->ObtenerSiguienteRegistro($query);
				$datosvalidados["cantroles"]=$numfilas;
				$datosvalidados["rolcod"]=$filarol["rolcod"];
				$datosvalidados["usuariosistemacod"]="";

				break;			
				
			case USUARIOBAJA :
				echo "<br /><br /><br />";
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Imposible acceder.  El usuario '". FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarionombre']." ".$filausuario['usuarioapellido'],ENT_QUOTES)."' ha sido dado de baja",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				echo "<p>&nbsp;</p>";
				return false;
				
				break;

			case USUPENDACT :
				ob_end_clean();
				$_SESSION['usuarioiderror'] = $_POST['usuarioid'];
				header("Location:index.php?msg_error=1&usuarioiderror=".$_POST['usuarioid']);
				die();
				return false;
				break;


			default:
				echo "<br /><br /><br />";
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Imposible acceder.  Estado del usuario '". FuncionesPHPLocal::HtmlspecialcharsBigtree($filausuario['usuarionombre']." ".$filausuario['usuarioapellido'],ENT_QUOTES)."' indefinido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				
				return false;
		}
	}
	
	return true;
}
//----------------------------------------------------------------------------------------- 

$datosvalidados=array();
$accion="NADA";

//1-valido el login
if (LOGINCAPTCHA==1)
{
	if (!isset($_POST['g-recaptcha-response']) || $_POST['g-recaptcha-response']=="")
	{
		header("Location:index.php?msg_error=3&usuarioiderror=".$_POST['usuarioid']);
		return false;
	}
	
	
	$objetoCurl = new \ReCaptcha\RequestMethod\CurlPost();
	$recaptcha = new \ReCaptcha\ReCaptcha(PRIVATEKEYCAPTCHA,$objetoCurl);
	
	$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
	if (!$resp->isSuccess())
	{
		foreach ($resp->getErrorCodes() as $code) {
			echo '<tt>' , $code , '</tt> ';
		}
		header("Location:index.php?msg_error=4&usuarioiderror=".$_POST['usuarioid']);
		die();
	}
}
		
if(ValidarDatosLogin($conexion,$datosvalidados,$usuarios))
{
	$_SESSION['usuariocod'] = $datosvalidados['usuariocod'];
	$_SESSION['usuarioid'] = $datosvalidados['usuarioid'];
	$_SESSION['usuarionombre'] = $datosvalidados['usuarionombre'];
	$_SESSION['usuarioapellido'] = $datosvalidados['usuarioapellido'];
	if(isset ($datosvalidados['avatar']) && $datosvalidados['avatar']!="")
		$_SESSION['avatar'] = $datosvalidados['avatar'];


	if($datosvalidados["cantroles"]==0)
	{
		$_SESSION['usuariocod']=0;
		echo "<div align='center'>No le han asignado ningún rol en el sistema. Comuníquese con su administrador</div>";
		$accion="NADA";
	}
	else
	{
		$_SESSION['mostrarmsgbloqueo'] ="SI";
		$_SESSION['rolcod']=$datosvalidados['rolcod'];
		$_SESSION['usuariosistemacod']=$datosvalidados['usuariosistemacod'];
		$oUsuariosAccesos = new cUsuariosAccesos($conexion);
		$oUsuariosAccesos->Insertar();
		header("Location:ingreso.php");
		die();
	}
}
else
{ // error al validar los datos
	$_SESSION['usuariocod']=0;
	$_SESSION['rolcod']=0;
	echo "<div align='center'><a href='index.php' class='textotabla linkfondoblanco'>Volver al Inicio</a></div>";
	$accion="NADA";
}

ob_end_flush();
die();
$oEncabezados->PieMenuEmergente();
 

?>

