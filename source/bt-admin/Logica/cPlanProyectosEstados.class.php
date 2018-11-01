<?php 
include(DIR_CLASES_DB."cPlanProyectosEstados.db.php");

{

	protected $formato;

		$this->conexion = &$conexion;
		$this->formato = &$formato;
		parent::__construct();
	}


	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
			return false;
		return true;
	}



	{
		$sparam=array(
			'xplanproyectoestadocod'=> 0,
			'planproyectoestadocod'=> "",
			'xplanproyectoestadonombre'=> 0,
			'planproyectoestadonombre'=> "",
			'xplanproyectoestadoestado'=> 0,
			'planproyectoestadoestado'=> "-1",
			'limit'=> '',
			'orderby'=> "planproyectoestadocod DESC"
		);

		{
			$sparam['planproyectoestadocod']= $datos['planproyectoestadocod'];
			$sparam['xplanproyectoestadocod']= 1;
		}
		if(isset($datos['planproyectoestadonombre']) && $datos['planproyectoestadonombre']!="")
		{
			$sparam['planproyectoestadonombre']= $datos['planproyectoestadonombre'];
			$sparam['xplanproyectoestadonombre']= 1;
		}
		if(isset($datos['planproyectoestadoestado']) && $datos['planproyectoestadoestado']!="")
		{
			$sparam['planproyectoestadoestado']= $datos['planproyectoestadoestado'];
			$sparam['xplanproyectoestadoestado']= 1;
		}


			$sparam['orderby']= $datos['orderby'];

			$sparam['limit']= $datos['limit'];

			return false;
		return true;
	}



	{
		if (!$this->_ValidarInsertar($datos))
			return false;

		$this->_SetearNull($datos);
		if (!parent::Insertar($datos,$codigoinsertado))
			return false;
		$datos['planproyectoestadocod'] =$codigoinsertado;
		if (!$this->Publicar($datos))
			return false;

	}



	{
		if (!$this->_ValidarModificar($datos))
			return false;

		if (!parent::Modificar($datos))
			return false;

			return false;

	}



	{
		if (!$this->_ValidarEliminar($datos))
			return false;

		$datosmodif['planproyectoestadoestado'] = ELIMINADO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	{
		if (!parent::ModificarEstado($datos))
			return false;
		if (!$this->Publicar($datos))
			return false;

	}



	{
		$datosmodif['planproyectoestadocod'] = $datos['planproyectoestadocod'];
		$datosmodif['planproyectoestadoestado'] = ACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	{
		$datosmodif['planproyectoestadocod'] = $datos['planproyectoestadocod'];
		$datosmodif['planproyectoestadoestado'] = NOACTIVO;
		if (!$this->ModificarEstado($datosmodif))
			return false;
		return true;
	}



	{
		if (!$this->PublicarListadoJson())
			return false;
		if (!$this->PublicarJsonxCodigo($datos))
			return false;
		return true;
	}



	{
		$datosJson = FuncionesPHPLocal::DecodificarUtf8($array);
		$jsonData = json_encode($datosJson);
		if(!is_dir($carpeta)){
			@mkdir($carpeta);
		}
		if(!FuncionesPHPLocal::GuardarArchivo($carpeta,$jsonData,$nombrearchivo.".json"))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_INF,"Error, al generar el archivo json. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}



	{
		if(file_exists($carpeta.$nombrearchivo.".json"))
		{
			unlink($carpeta.$nombrearchivo.".json");
		}
		return true;
	}



	{
		$nombrearchivo = "plan_proyectos_estados";
		$carpeta = PUBLICA."json/";
		if(!$this->GerenarArrayDatosJsonListado($array))
			return false;
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	{
		$array = array();
		$datos['planproyectoestadoestado'] = ACTIVO;
		if(!$this->BusquedaAvanzada($datos,$resultados,$numfilas))
			return false;
		if($numfilas>0)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planproyectoestadocod']] = $fila;
			}
		}
		return true;
	}



	{
		$nombrearchivo = "plan_proyectos_estados_".$datos['planproyectoestadocod'];
		$carpeta = PUBLICA."json/";
		if(!$this->GerenarArrayDatosJsonxCodigo($datos,$array))
			return false;
		if(count($array)>0)
		{
			if(!$this->GuardarDatosJson($nombrearchivo,$carpeta,$array))
				return false;
		}
		else
		{
			if(!$this->EliminarDatosJson($nombrearchivo,$carpeta))
				return false;
		}
		return true;
	}



	{
		$array = array();
		if(!$this->BuscarxCodigo($datos,$resultados,$numfilas))
			return false;
		if($numfilas==1)
		{
			while($fila = $this->conexion->ObtenerSiguienteRegistro($resultados))
			{
				$array[$fila['planproyectoestadocod']] = $fila;
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

	}



	{
		if (!$this->BuscarxCodigo($datos,$resultado,$numfilas))
			return false;

		if ($numfilas!=1)
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error debe ingresar un codigo valido.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		if (!$this->_ValidarDatosVacios($datos))
			return false;

	}



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



	{


		if (!isset($datos['planproyectoestadonombre']) || $datos['planproyectoestadonombre']=="")
			$datos['planproyectoestadonombre']="NULL";

		if (!isset($datos['planproyectoestadocolor']) || $datos['planproyectoestadocolor']=="")
			$datos['planproyectoestadocolor']="NULL";
		return true;
	}



	{


		if (!isset($datos['planproyectoestadonombre']) || $datos['planproyectoestadonombre']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un nombre",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}

		if (!isset($datos['planproyectoestadocolor']) || $datos['planproyectoestadocolor']=="")
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Debe ingresar un color",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		return true;
	}





?>