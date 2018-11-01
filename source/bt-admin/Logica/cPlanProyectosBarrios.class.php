<?php 
include(DIR_CLASES_DB."cPlanProyectosBarrios.db.php");

class cPlanProyectosBarrios extends cPlanProyectosBarriosdb
{

	protected $conexion;
	protected $formato;

	function __construct($conexion,$formato=FMT_TEXTO){
		$this->conexion = &$conexion;
		$this->formato = &$formato;
		parent::__construct();
	}

	function __destruct(){parent::__destruct();}

	public function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		return true;
	}

	public function BuscarComunas($datos,&$resultado,&$numfilas)
	{
		if (!parent::BuscarComunas($datos,$resultado,$numfilas))
			return false;
		return true;
	}



	public function BusquedaAvanzada($datos,&$resultado,&$numfilas)
	{
		$sparam=array(
			'xplanproyectocod'=> 0,
			'planproyectocod'=> "",
			'xbarriocod'=> 0,
			'barriocod'=> "",
			'limit'=> '',
			'orderby'=> "planproyectocod DESC"
		);

		if(isset($datos['planproyectocod']) && $datos['planproyectocod']!="")
		{
			$sparam['planproyectocod']= $datos['planproyectocod'];
			$sparam['xplanproyectocod']= 1;
		}
		if(isset($datos['barriocod']) && $datos['barriocod']!="")
		{
			$sparam['barriocod']= $datos['barriocod'];
			$sparam['xbarriocod']= 1;
		}


		if(isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['orderby']= $datos['orderby'];

		if(isset($datos['limit']) && $datos['limit']!="")
			$sparam['limit']= $datos['limit'];

		if (!parent::BusquedaAvanzada($sparam,$resultado,$numfilas))
			return false;
		return true;
	}


	public function Insertar($datos)
	{
		
		if (!$this->_ValidarInsertar($datos))
			return false;

		$this->_SetearNull($datos);
		if (!parent::Insertar($datos))
			return false;
			
		
		$oComunasBarrios = new cGcbaComunaBarrios($this->conexion);
		if(!$oComunasBarrios->BusquedaAvanzada($datos,$resultado,$numfilas))
			return false;
		if($numfilas==0)
			return false;
		$fila=$this->conexion->ObtenerSiguienteRegistro($resultado);
		$datos['comunacod'] = $fila['comunacod'];
		$oProyectosComunas = new cPlanProyectosComunas($this->conexion);
		if(!$oProyectosComunas->Insertar($datos))
			return false;
		return true;
	}


	public function Eliminar($datos)
	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		if (!parent::Eliminar($datos))
			return false;
		
		if(!$this->BuscarComunas($datos,$resultado,$numfilas))
			return false;
		$arrayComunasNuevo = array();
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))
			$arrayComunasNuevo[]=$fila['comunacod'];
			
		$oProyectosComunas = new cPlanProyectosComunas($this->conexion);
		if(!$oProyectosComunas->BusquedaAvanzada($datos,$resultadoComuna,$numfilasComuna))
			return false;
		$arrayComunasActual = array();
		
		while($fila = $this->conexion->ObtenerSiguienteRegistro($resultadoComuna))
			$arrayComunasActual[]=$fila['comunacod'];
		
		$arrayComunasEliminar = array_diff($arrayComunasActual,$arrayComunasNuevo);
		//print_r($arrayComunasEliminar);die;
		$datosEliminar['planproyectocod'] = $datos['planproyectocod'];
		if(count($arrayComunasEliminar)>0)
		{
			foreach($arrayComunasEliminar as $comunacod)
			{
				$datosEliminar['comunacod'] = $comunacod;
				if(!$oProyectosComunas->Eliminar($datosEliminar))
					return false;
			}
		}
		return true;
	}




//-----------------------------------------------------------------------------------------
//FUNCIONES PRIVADAS
//-----------------------------------------------------------------------------------------

	private function _ValidarInsertar($datos)
	{
		if (!$this->_ValidarDatosVacios($datos))
			return false;
			
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas==1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error el barrio ya existe.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}	

		return true;
	}


	private function _ValidarEliminar($datos)
	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un c&oacute;digo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;
	}



	private function _SetearNull(&$datos)
	{

		if (!isset($datos['planproyectocod']) || $datos['planproyectocod']=="")
			$datos['planproyectocod']="NULL";
		if (!isset($datos['barriocod']) || $datos['barriocod']=="")
			$datos['barriocod']="NULL";
		return true;
	}



	private function _ValidarDatosVacios($datos)
	{

		
		if (!isset($datos['planproyectocod']) || $datos['planproyectocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un código de proyecto",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['planproyectocod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		if (!isset($datos['barriocod']) || $datos['barriocod']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un código de barrio",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!FuncionesPHPLocal::ValidarContenido($this->conexion,$datos['barriocod'],"NumericoEntero"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo numerico.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		$oGcbaBarrios = new cGcbaBarrios($this->conexion,$this->formato);
		
		 if(!$oGcbaBarrios->BuscarxCodigo($datos,$result,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un campo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}





}
?>