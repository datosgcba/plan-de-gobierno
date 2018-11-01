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


$oLogMensajes=new cLogMensajes($conexion);

?>
<div class="onecolumn">
    <div class="header">
        <span>Log Mensajes</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 
//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,$oLogMensajes)
{
	if (isset($_POST['botonbuscar']))
	{
		if ($_POST['codigo_mensaje']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el C�digo de Mensaje. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	
	}
	if (isset($_POST['botonalta']))
	{
		if ($_POST['codigo_mensaje']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el C�digo de Mensaje. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['descripcion']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la Descripci�n. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['nivel']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el Nivel. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	} // $_POST['botonalta']
	
	if (isset($_POST['botonbaja'])) 	
	{
		if ($_POST['codigo']=="" || !FuncionesPHPLocal::ValidarContenido($conexion,$_POST['codigo'],"NumericoEntero") )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error en el c�digo del Log Mensaje. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if($_POST['codigo']!="") // validar que exista el modulocod
		{
			$codigo=$_POST['codigo'];
			$ArregloDatos['codigo_mensaje']=$codigo;	
			if (!$oLogMensajes->Buscar ($ArregloDatos,$numfilas,$resultado))
				return false;
			
			if ($numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo de Log Mensaje inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
			}
		}	
	} // $_POST['botonbaja']

	return true;
}
$result=true;
	if (!Validarphp($conexion,$oLogMensajes))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","LogMensaje - codigo=".$_POST['codigo'],$_SESSION['usuariocod']);	
		$result=false;
	}


	if (isset ($_POST['botonbuscar']))
	{
		$carpetas=explode ("||",DIR_BUSQUEDA);
		$codigolog=$_POST['codigo_mensaje'];
		$textoprincipal="El c�digo de mensaje buscado se encuentra en las carpetas: ";
		if (!$oLogMensajes->VerificarExistencia ($codigolog,$textoprincipal,$carpetas,$encontrado,$texto))
		{	
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,$error,array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			$result=false;
		}
		if ($encontrado)
			echo $texto;
		else
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_INF,"No se encuentra en ninguna carpeta",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	}
//----------------------------------------------------------------------------------------- 		
	if ($result &&  ((isset($_POST['botonalta']) || isset($_POST['botonbaja']))))
	{
		$commit=true;
		$errorinformable=true;
		
		$conexion->ManejoTransacciones("B");
	
		// Validaci�n de los datos 
		// Baja del Archivo
		if($result && isset($_POST['botonbaja'])) 
		{
				if ($result)
				{
					$carpetas=explode ("||",DIR_BUSQUEDA);
					$codigolog=$_POST['codigo_mensaje'];
					$textoprincipal="El c�digo de mensaje a borrar se encuentra en las carpetas: ";
					if (!$oLogMensajes->VerificarExistencia ($codigolog,$textoprincipal,$carpetas,$encontrado,$texto))
						$result=false;

					if ($encontrado)
					{
						$result=false;
						echo $texto;
					}
					if ($result)
					{
						$codigo=$_POST['codigo'];
						$ArregloDatos['codigo_mensaje']=$codigo;	
						if (!$oLogMensajes->Buscar ($ArregloDatos,$numfilas,$resultado))
							$result=false;

					}
					if ($result)
					{
						if ($numfilas!=1)
						{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo de Stored Procedure inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
						}
					}
					if ($result)
					{
						if (!$oLogMensajes->Eliminar ($ArregloDatos))
							$result=false;

						$datos=$conexion->ObtenerSiguienteRegistro($resultado);
						if($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"034702","codigo=".$codigo,$_SESSION['usuariocod']);	
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado el Log Mensaje '".$codigo."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
					$conexion->ManejoTransacciones("C");
				}
			
		} // botonbaja

		// Alta o Actualizaci�n
		if($result && isset($_POST['botonalta'])) 
		{
			
			if($_POST['codigo']=="") 
			{
				$ArregloDatos['codigo_mensaje']=$_POST['codigo_mensaje'];	
				if (!$oLogMensajes->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas==1)
					{
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"El C�digo de Mensaje ya se encuentra en la Tabla de Log Mensajes.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($result)
					{
						
						$ArregloDatos['descripcion']=$_POST['descripcion'];	
						$ArregloDatos['nivel']=$_POST['nivel'];	
						if (!$oLogMensajes->Insertar ($ArregloDatos,$codigolog))
							$result=false;
						
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"034701","codigo=".$codigolog,$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado el Log Mensaje '".$_POST['descripcion']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			}else
			{	
				$codigo=$_POST['codigo'];
				$ArregloDatos['codigo_mensaje']=$codigo;	
				if (!$oLogMensajes->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas!=1)
					{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo de Log Mensaje inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
					}
					if ($result)
					{
						if ($result)
						{
							$ArregloDatos['descripcion']=$_POST['descripcion'];
							$ArregloDatos['nivel']=$_POST['nivel'];	
							if (!$oLogMensajes->Modificar ($ArregloDatos))
								$result=false;

						}
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"034703","codigo=".$codigo,$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado el Log Mensaje '".$_POST['descripcion']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			} // botonalta	
		}
		if($result)
			$conexion->ManejoTransacciones("C");
		else
			$conexion->ManejoTransacciones("R");
		
	} // $_POST['botonalta'] || $_POST['botonbaja']

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

?>
		<br /><br /><br /><div align="center"><a href="logmensajes.php" class="linkfondoblanco">Volver</a></div>
   </div>
</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
