<?php  
include(DIR_CLASES_DB."cCategoriasModulos.db.php");

class cCategoriasModulos extends cCategoriasModulosdb	
{
	protected $conexion;
	protected $formato;

	
// CLASE cPaginasModulos 

//-----------------------------------------------------------------------------------------

	// Constructor de la clase
	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = $formato;
		parent::__construct(); 
    } 
	
	// Destructor de la clase
	function __destruct() {	
		parent::__destruct(); 
    } 	

	
	
	
//-----------------------------------------------------------------------------------------
//							 PUBLICAS	
//----------------------------------------------------------------------------------------- 



//------------------------------------------------------------------------------------------	
// Retorna en un query con todos los modulos de una pagina

// Par�metros de Entrada:
//		pagcod: codigo de la pagina

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	public function BuscarxCategoria($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCategoria($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 
//------------------------------------------------------------------------------------------	
// Retorna en un query con todos los modulos de una pagina

// Par�metros de Entrada:
//		pagcod: codigo de la pagina

// Retorna:
//		resultado= Arreglo con todos los datos de un categoria.
//		numfilas= cantidad de filas 
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no


	public function BuscarModuloxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarModuloxCodigo($datos,$resultado,$numfilas))
			return false;
		
		return true;
	} 

//----------------------------------------------------------------------------------------- 
//ABM DE PAGINAS MODULOS.-
//----------------------------------------------------------------------------------------- 



//----------------------------------------------------------------------------------------- 
// Inserta un modulo nuevo en una pagina.

// Par�metros de Entrada:

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	
	public function ActualizarInsertar($datos,&$codigoinsertado)
	{

		if(!$this->BuscarxCategoria($datos,$resultado,$numfilas))
			return false;

		$datosmodif['moduloorden'] = 1;
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			if ($datosmodif['moduloorden']==$datos['moduloorden'])
				$datosmodif['moduloorden']++;
				
			$datosmodif['catmodulocod'] = $fila['catmodulocod'];
			if (!parent::ModificarOrden($datosmodif))
				return false;
			
			$datosmodif['moduloorden']++;
		}
		
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
				
		return true;
	} 
	



//----------------------------------------------------------------------------------------- 
// Inserta un modulo nuevo en una pagina.

// Par�metros de Entrada:

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	
	public function Insertar($datos,&$codigoinsertado)
	{
		if (!$this->_ValidarDatosAlta($datos))
			return false;

		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
			
		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Modifica los modulos de una pagina asignadso

// Par�metros de Entrada:

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	
	public function Modificar($datos)
	{
		
		if (!$this->_ValidarDatosModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;
			

		return true;
	} 
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar un modulo de una pagina 

// Par�metros de Entrada:
//		pagmodulocod = codigo de modulo pagina.

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no

	public function Eliminar($datos)
	{
		if (!$this->_ValidarDatosEliminar($datos))
			return false;
			
		if (!parent::Eliminar($datos))
			return false;
		
			
		return true;
	}
//----------------------------------------------------------------------------------------- 
// Retorna true o false si pudo eliminar un modulo de una pagina xpagcod

// Par�metros de Entrada:
//		pagcod = codigo de  pagina.

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no

	public function EliminarxCategoria($datos)
	{

		if (!parent::EliminarxCategoria($datos))
			return false;
			
		//si existen columnas html asociadas, las elimino
		if (!$this->EliminarColumnas($datos))
			return false;
			
		return true;
	}	
	
	
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Insertar una nueva noticia publicada

// Par�metros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no

	public function GenerarColumnas($datos)
	{
		if (!$this->BuscarxCategoria($datos,$resultado,$numfilas))
			return false;	

		$htmlModuleRender = "";
		while ($datosmodulo = $this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$datosmodulo['conexion'] = $this->conexion;
			$htmlModuleRender .= FuncionesPHPLocal::RenderFile("tapas_modulos/html/".$datosmodulo['moduloarchivo'],$datosmodulo);
		}

		if(!FuncionesPHPLocal::GuardarArchivo(PUBLICA."secciones/",$htmlModuleRender,"seccion_".$datos['catcod'].".html"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo de la seccion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 

// Eliminar un archivo de columna

// Par�metros de Entrada:
//		datos: array asociativo con los datos a agregar

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no

	public function EliminarColumnas($datos)
	{
		if (file_exists(PUBLICA."secciones/seccion_".$datos['catcod'].".html"))
		{
			if(!unlink(PUBLICA."secciones/seccion_".$datos['catcod'].".html"))
			{
				FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al eliminar el archivo de la seccion. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
				return false;
			}
		}

		return true;
	}
	
	
//----------------------------------------------------------------------------------------- 
//Retorna true o false si pudo cambiar el orden de los modulos paginas

// Par�metros de Entrada:
//		moduloorden = orden de los modulos de las paginas.

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no		
	public function ModificarOrden($datos)
	{
		$datosmodif['moduloorden'] = 1;
		foreach ($datos['modulo'] as $catmodulocod)
		{
			$datosmodif['catmodulocod'] = $catmodulocod;
			if (!parent::ModificarOrden($datosmodif))
					return false;
			$datosmodif['moduloorden']++;
		}
		return true;
	}
	
	

	
	
//----------------------------------------------------------------------------------------- 
// Retorna true o false

// Par�metros de Entrada:
 		 

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	private function _ValidarDatosVacios($datos)
	{
		return true;
	}

	
//----------------------------------------------------------------------------------------- 
// Retorna true o false

// Par�metros de Entrada:
 		 

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	private function _ValidarDatosAlta($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;


		return true;
	}

//----------------------

// Par�metros de Entrada:
	 

// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no

	private function _ValidarDatosModificar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;

		return true;
	}


//----------------------------------------------------------------------------------------- 


// Par�metros de Entrada:


// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no
	private function _ValidarDatosEliminar($datos)
	{
		return true;
	}



//----------------------------------------------------------------------------------------- 
// Retorna proxorden. proximo orden del modulo pagina

// Par�metros de Entrada:


// Retorna:
//		la funci�n retorna true o false si se pudo ejecutar con �xito o no	
	
	private function ObtenerProximoOrden($datos,&$proxorden)
	{
		$proxorden = 0;
		if (!parent::BuscarModuloCategoriaUltimoOrden($datos,$resultado,$numfilas))
			return false;
			
		if ($numfilas!=0)
		{
			$datos = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$proxorden = $datos['maximo'] + 1;
		}
		return true;
	}
	

}// FIN CLASE

?>