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


$oConstantesGrales=new cConstantesGrales($conexion);

?>
<div class="onecolumn">
    <div class="header">
        <span>Constantes Generales</span>
    </div>
    <div class="content base clearfix">
    <br class="clear" />
<?php 
//----------------------------------------------------------------------------------------- 
function Validarphp($conexion,$oConstantesGrales)
{
	if (isset($_POST['botonbuscar']))
	{
		if ($_POST['codigo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el C�digo de Constante. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
	
	}
	if (isset($_POST['botonalta']))
	{
		if ($_POST['sistemanom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el Nomber del Sistema. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['constantetipo']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el tipo de Constante. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['constantecod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el C�digo de Constante. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['constantenom']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en el Nombre de la Constante. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if ($_POST['constantedesc']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"Error en la Descripci�n. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		
	} // $_POST['botonalta']
	
	if (isset($_POST['botonbaja'])) 	
	{
		if ($_POST['codigo']=="" )
		{
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error en el c�digo la Constante Gral.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
			return false;
		}
		if($_POST['codigo']!="") // validar que exista el modulocod
		{
			$codigo=$_POST['codigo'];
			$datosencontrados=explode (" - ",$codigo);
			$ArregloDatos['constantetipo']=$datosencontrados[0];	
			$ArregloDatos['constantecod']=$datosencontrados[1];	
			if (!$oConstantesGrales->Buscar ($ArregloDatos,$numfilas,$resultado))
				return false;
			
			if ($numfilas!=1)
			{
				FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo de Constante Gral. inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
				return false;
			}
		}	
	} // $_POST['botonbaja']

	return true;
}
$result=true;
	if (!Validarphp($conexion,$oConstantesGrales))
	{
		FuncionesPHPLocal::RegistrarAcceso($conexion,"040099","Constantes Generales - codigo=".$_POST['codigo'],$_SESSION['usuariocod']);	
		$result=false;
	}

	if (isset ($_POST['botonbuscar']))
	{
					$carpetas=explode ("||",DIR_BUSQUEDA);
					$codigolog=$_POST['constantenom'];
					$textoprincipal="El c�digo de constante buscado se encuentra en las carpetas: ";
					if (!$oConstantesGrales->VerificarExistencia ($codigolog,$textoprincipal,$carpetas,$encontrado,$texto))
						$result=false;

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
					$codigo=$_POST['codigo'];
					$datosencontrados=explode (" - ",$codigo);
					$ArregloDatos['constantetipo']=$datosencontrados[0];	
					$ArregloDatos['constantecod']=$datosencontrados[1];	
					if (!$oConstantesGrales->Buscar($ArregloDatos,$numfilas,$resultado))
						$result=false;

					if ($result)
					{
						if ($numfilas!=1)
						{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo de Constante General inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
						}
					}
					if ($result)
					{
						if (!$oConstantesGrales->Eliminar ($ArregloDatos))
							$result=false;

						$datos=$conexion->ObtenerSiguienteRegistro($resultado);
						if($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"032702","codigo=".$codigo,$_SESSION['usuariocod']);	
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha eliminado la Constante General '".$codigo."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
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
				$ArregloDatos['constantetipo']=$_POST['constantetipo'];	
				$ArregloDatos['constantecod']=$_POST['constantecod'];	
				if (!$oConstantesGrales->BuscarDatos ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				if ($result)
				{
					if ($numfilas==1)
					{
						FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"La Constante ya se encuentra en la Tabla de Constantes Generales.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						$result=false;
					}
					if ($result)
					{
						
						$ArregloDatos['sistemanom']=$_POST['sistemanom'];	
						$ArregloDatos['constantenom']=$_POST['constantenom'];	
						$ArregloDatos['constantedesc']=$_POST['constantedesc'];
						if (!$oConstantesGrales->Insertar ($ArregloDatos,$codigo))
							$result=false;

						
						if ($result)
						{
							FuncionesPHPLocal::RegistrarAcceso($conexion,"032701","Constantenom=".$_POST['constantenom'],$_SESSION['usuariocod']);
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha insertado la Constante Gral. '".$_POST['constantenom']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
						}
					}
				}
			}else
			{	
				$codigo=$_POST['codigo'];
				$datosencontrados=explode (" - ",$codigo);
				$ArregloDatos['constantetipo']=$datosencontrados[0];	
				$ArregloDatos['constantecod']=$datosencontrados[1];	
				if (!$oConstantesGrales->Buscar ($ArregloDatos,$numfilas,$resultado))
					$result=false;

				$datos=$conexion->ObtenerSiguienteRegistro ($resultado);
				if ($result)
				{
					if ($numfilas!=1)
					{
							FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo de Constante General inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
							$result=false;
					}
					
					$Arreglo['constantetipo']=$_POST['constantetipo'];	
					$Arreglo['constantecod']=$_POST['constantecod'];	
					if (($datos['constantetipo']!=$Arreglo['constantetipo']) || ($Arreglo['constantecod']!=$ArregloDatos['constantecod']))
					{
							if (!$oConstantesGrales->BuscarDatos ($Arreglo,$numfilas,$resultadodatosencontrados))
								$result=false;

							if ($result)
							{

								if ($numfilas>0)
								{
										FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRSOSP,"C�digo y Tipo de Constante General existente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
										$result=false;
								}
							}
					}		
						if ($result)
						{
							if ($result)
							{
								$ArregloDatos['sistemanom']=$_POST['sistemanom'];	
								$ArregloDatos['constantenom']=$_POST['constantenom'];	
								$ArregloDatos['constantedesc']=$_POST['constantedesc'];
								$ArregloDatos['constantetipomod']=$_POST['constantetipo'];	
								$ArregloDatos['constantecodmod']=$_POST['constantecod'];
								if (!$oConstantesGrales->Modificar ($ArregloDatos))
									$result=false;

							}
							if ($result)
							{
								FuncionesPHPLocal::RegistrarAcceso($conexion,"032703","constantenom=".$_POST['constantenom'],$_SESSION['usuariocod']);
								FuncionesPHPLocal::MostrarMensaje($conexion,MSG_OK,"Se ha actualizado la Constante General '".$_POST['constantenom']."'. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
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
		<br /><br /><br /><div align="center"><a href="constantesgenerales.php" class="linkfondoblanco">Volver</a></div>
    </div>
</div>        
<div style="height:20px; clear:both">&nbsp;</div>
<?php 
$oEncabezados->PieMenuEmergente();
?>
