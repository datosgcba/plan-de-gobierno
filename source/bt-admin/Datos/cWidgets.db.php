<?php  
abstract class cWidgetsdb
{
	
	// Constructor de la clase
	function __construct(){


    } 
	
	// Destructor de la clase
	function __destruct() {	
    } 	

	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 

//----------------------------------------------------------------------------------------- 
// Retorna el SP y los parametros para cargar los roles del sistema

// Par�metros de Entrada:

// Retorna:
//		spnombre,spparam
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	
	protected function Buscar($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_wid_widgets_xwidgetestado";
		$sparam=array(
			'pwidgetestado'=> $datos['widgetestado']
			);
		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los widgets. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	
	}




}


?>