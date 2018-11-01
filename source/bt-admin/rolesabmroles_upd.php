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

$oRoles_Abm_Roles=new cRolesAbmRoles ($conexion);
?>
<div class="onecolumn">
    <div class="header">
        <span>Roles - ABM Roles</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />

<?php 
//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,&$datosvalidados)
{
	$datosvalidados=array();
	
	if($_POST['rolcod']=="")
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la selecci�n del rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}

	if(!$conexion->BuscarRegistroxClave("sel_roles_orden",array("porderby"=>"rolcod"),array("rolcod"=>$_POST['rolcod']),$resultado,$filaret,$numfilasmatcheo,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	
	if($numfilasmatcheo!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error al seleccionar el rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosvalidados["rolcod"]=$_POST['rolcod'];

	if(isset($_POST['listarolesder']))
		$arrayfinal=$_POST['listarolesder'];
	else
		$arrayfinal=array();

	$arrayinicial=array();
	if(!$conexion->ejecutarStoredProcedure("sel_roles_xrolesactualizados",array('prolcodactualiza'=>$datosvalidados["rolcod"],'porderby'=>"rolcod"),$resultado,$numfilas,$errno))
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al seleccionar los m�dulos. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	while($filamodulos=$conexion->ObtenerSiguienteRegistro($resultado))
		$arrayinicial[]=$filamodulos['rolcod'];

	$arraysacar=array_diff($arrayinicial,$arrayfinal);
	$arrayponer=array_diff($arrayfinal,$arrayinicial);

	$datosvalidados["modulossacar"]=$arraysacar;
	$datosvalidados["modulosponer"]=$arrayponer;
	
	return true;
}

//----------------------------------------------------------------------------------------- 	

	$result=true;
	if(isset($_POST['botonalta']) && !Validarphp($conexion,$datosvalidados))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","",$_SESSION['usuariocod']);	
		$result=false;
	}
	if($result && isset($_POST['botonalta']))
	{
		$conexion->ManejoTransacciones("B");
		
		foreach($datosvalidados["modulosponer"] as $rolcodactualizado)
		{
			$ArregloDatos=array('rolcodactualiza'=>$datosvalidados['rolcod'],'rolcodactualizado'=>$rolcodactualizado);
			if (!$oRoles_Abm_Roles->Insertar ($ArregloDatos))
				$result=false;
			else	
				FuncionesPHPLocal::RegistrarAcceso($conexion,'034401',$datosvalidados['rolcod']."-".$rolcodactualizado,$_SESSION['usuariocod']);
		}

		if($result)
		{
			foreach($datosvalidados["modulossacar"] as $rolcodactualizado)
			{  
				$ArregloDatos=array('rolcodactualiza'=>$datosvalidados['rolcod'],'rolcodactualizado'=>$rolcodactualizado);
				if (!$oRoles_Abm_Roles->Eliminar ($ArregloDatos))
					$result=false;
				else
					FuncionesPHPLocal::RegistrarAcceso($conexion,'034402',$datosvalidados['rolcod']."-".$rolcodactualizado,$_SESSION['usuariocod']);
			}
		}
		
		if($result)
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se actualiz� el Rol-Abm-Rol. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			$conexion->ManejoTransacciones("C");
		}
		else
			$conexion->ManejoTransacciones("R");
	}
	

?>		
			<br /><br /><br /><div align="center"><a href="rolesabmroles.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 

$oEncabezados->PieMenuEmergente();
?>
