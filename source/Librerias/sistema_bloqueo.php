<?php 
//--------------------------------------------------------------------------
// Para el manejo del BLOQUEO DEL SISTEMA.
//--------------------------------------------------------------------------
class SistemaBloqueo
{
	var $MostrarMSGBloqueo;
	
//----------------------------------------------------------------------------------------- 
// Inicializa el objeto. Si no existe la variable de sesion, inicializa en SI

	function SistemaBloqueo()
	{ 
		if(isset($_SESSION['mostrarmsgbloqueo']))
			$this->MostrarMSGBloqueo=$_SESSION['mostrarmsgbloqueo'];
		else
			$this->MostrarMSGBloqueo="SI";
	}
	
//----------------------------------------------------------------------------------------- 
// Setea la variable de mostrarmsgbloqueo

	function setMostrarMSG($texto)
	{
		$MostrarMSGBloqueo=$texto;
		$_SESSION['mostrarmsgbloqueo']=$texto;
	}
	
//----------------------------------------------------------------------------------------- 
// Muestra el mensaje de Bloqueo

	function MostrarAviso($aviso,$fecha)
	{
		?>
		<!-- Para el mensaje de aviso que el sistema se bloquear� -->
		<style type="text/css">
		.avisobloqueo {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 18px;
			font-weight: bold;
			color: #FFFFFF;
			background-color: #FF0000;
			background-position: center;
		}
		</style>
		<?php 
		echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' >
			 <tr> <td class='avisobloqueo'>";
			  echo 'El sistema se bloquear� el '  .substr($fecha,8,2)."/".substr($fecha,5,2)."/".substr($fecha,0,4).' a las '.substr($fecha,10,6).' hs. <br />';				
			  echo $aviso."<br />";  		  
		echo "</td></tr></table>";
	}

//--------------------------------------------------------------------------
//  Verifica si la base esta activa (tabla:sistema) 

// 1) En caso de NO estar activa verifica si
//    *) Si esta fuera de linea : Termina con la session y muestra mensaje
//    *) Si todavia en linea: Muestra aviso de pr�ximamente ser� sacado de linea.

	function VerificarActivo($conexion,&$activo,&$mensaje,$muestromensaje) 
	{
		$param=array();
		if(!$conexion->ejecutarStoredProcedure('sel_sistema',$param,$resultado,$numfilas,$errno))
			die("Se produjo un error al acceder al sistema - ".$errno);
		if($numfilas!=1) 
		{ 
			FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error en la tabla sistema.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array('formato'=>FMT_TEXTO));
			$activo = "SI";
		}	
		else 
		{
			$fila = mysql_fetch_assoc($resultado);
			if ($fila['sis_activo'] == "SI") 
				$activo = "SI";
			else 
			{		
				$ahora = strtotime(date("Y-m-d H:i:s"));
				$inicio = strtotime($fila['sis_fechainicio']);
				if ($ahora >= $inicio) 
				{
					$activo  = "NO";
					$mensaje = $fila['sis_mensaje'];
// OJO - ver si es necesario inicializar el objeto de sesion					
					$_SESSION=array();
					session_destroy();
				}
				else 
				{
					$activo = "SI";
					if ($muestromensaje=="SI")
						$this->MostrarAviso($fila['sis_aviso'],$fila['sis_fechainicio']);
				}				
			} // if activo		
		} // if cant de filas de sistema
	} // fin funcion
	
//--------------------------------------------------------------------------
// Verifico si esta bloqueado. Si es asi, finaliza el script.

	function VerificarBloqueo($conexion)
	{
		if($this->MostrarMSGBloqueo == "SI")
		{
			$this->VerificarActivo ($conexion,$activo,$mensaje,"SI");
		
			if ($activo == "NO") 
			{
				echo $mensaje;
				die;
			}
		}
	}
	
} // fin clase
?>