<?
include(DIR_DATA."paginasData.php");

//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las paginas

class cPaginas
{
	protected $conexion;
	protected $datospagina;
	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	


	
	public function SetData(&$oPaginasData,$datos)
	{
		if (isset($datos['pagcuerpoprocesado']))
			$oPaginasData->setCuerpo($datos['pagcuerpoprocesado']);

		if (isset($datos['pagcod']))
			$oPaginasData->setCodigo($datos['pagcod']);
	
		if (isset($datos['categoria']))
			$oPaginasData->setCategoria($datos['categoria']);
	
		if (isset($datos['catcod']))
			$oPaginasData->setCodigoCategoria($datos['catcod']);
		
		if (isset($datos['pagtitulo']))
			$oPaginasData->setTitulo($datos['pagtitulo']);
		
		if (isset($datos['pagsubtitulo']))
			$oPaginasData->setSubtitulo($datos['pagsubtitulo']);

		if (isset($datos['pagtitulocorto']))
			$oPaginasData->setTituloCorto($datos['pagtitulocorto']);
		
		if (isset($datos['pagcopete']))
			$oPaginasData->setCopete($datos['pagcopete']);

		if (isset($datos['pagcuerpoprocesado']))
			$oPaginasData->setCuerpo($datos['pagcuerpoprocesado']);

		if (isset($datos['muestramenu']))
			$oPaginasData->setMuestraMenu($datos['muestramenu']);

		if (isset($datos['pagestadocod']))
			$oPaginasData->setEstado($datos['pagestadocod']);

		if (isset($datos['pagdominio']))
			$oPaginasData->setDominio($datos['pagdominio']);

		if (isset($datos['planthtmlcod']))
			$oPaginasData->setPlantillaHtmlCodigo($datos['planthtmlcod']);

		if (isset($datos['menucod']))
			$oPaginasData->setMenuCodigo($datos['menucod']);

		if (isset($datos['menutipocod']))
			$oPaginasData->setMenuTipoCodigo($datos['menutipocod']);
			
		return true;
	}



	public function BuscarPagina($datos)
	{

		$spnombre="sel_pag_paginas_publicadas_xcodigo";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datospagina = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$oPaginasData = new PaginasData();
		$this->SetData($oPaginasData,$datospagina);

		return $oPaginasData;	
	}
	
	
	
	public function BuscarPaginaPrevisualizacion($datos)
	{
		$spnombre="sel_pag_paginas_xcodigo";
		$sparam=array(
			'ppagcod'=> $datos['pagcod']
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		if ($numfilas!=1)
			return false;
			
		$datospagina = $this->conexion->ObtenerSiguienteRegistro($resultado);
		$oPaginasData = new PaginasData();
		$this->SetData($oPaginasData,$datospagina);

		return $oPaginasData;	
	}

			
	public function BuscarHermanoseHijos($oPaginas,&$resultado,&$numfilas)
	{
		$spnombre="sel_pag_paginas_publicadas_arbol_xpagcodsuperior_catcod";
		$sparam=array(
			'pcatcod'=> $oPaginas->getCodigoCategoria(),
			'pxpagcodsuperior'=> 0,
			'ppagcodsuperior'=> ""
			);
		if ($oPaginas->getCodigo()!="")
		{
			$sparam['pxpagcodsuperior'] = 1;
			$sparam['ppagcodsuperior'] = $oPaginas->getCodigo();
		}

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		return true;	
	}

	public function CargarImagenes(&$oPaginasData,$cantidad=NULL)
	{
			
		$spnombre="sel_pag_paginas_mul_multimedia_xpagcod";
		$sparam=array(
			'ppagcod'=> $oPaginasData->getCodigo(),
			'pmultimediaconjuntocod'=> FOTOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las imagenes de la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$imagenes = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$imagenes[] = $oMultimedia;
			unset($oMultimedia);
		}
		unset($oMultimediaService);
		$oPaginasData->setImagenes($imagenes);


		return true;	
	}
			
	public function ArregloHijos($oPaginas,&$arrcat)
	{
		$arrcat=array();
		$this->BuscarHermanoseHijos($oPaginas,$resultado,$numfilas);
		
		$cantidadarreglo=0;
		while ($filasub=$this->conexion->ObtenerSiguienteRegistro($resultado))
		{
			$oPaginasData = new PaginasData();
			$this->SetData($oPaginasData,$filasub);
			$arrcat[$cantidadarreglo]['datos']=$oPaginasData;
			$cantidadarreglo++;
			unset($oPaginasData);
		}
		
		
		return true;	
	}
	
	
	
	//----------------------------------------------------------------------------------------- 
	// Retorna un array con todo el arbol dependiente del pagcod ingresado
	
	// Parámetros de Entrada:
	//		pagcod: raiz del arbol a retornar. Si vale "", entonces retorna el arbol completo de la página
	
	// Retorna:
	//		arbol: array con el resultado de la consulta.
	//					Además de la información del categoria, se agregan los subindices:
	//						subarbol: arbol con las páginas dependientes de la pagina
	//						ruta: jerarquia ascendente desde el categoria actual hasta la raiz
	//		la función retorna true o false si se pudo ejecutar con éxito o no

	public function ArmarArbolPaginas($oPaginas,&$arbol,$arreglopadre=array())
	{
		//traigo primero todos los hijos dela página solicitada
		$total=0;
		if(!$this->ArregloHijos($oPaginas,$arbol))
			return false;
		
		//recorro todos las páginas para asignar la ruta y armar el subarbol dependiente
		
		foreach($arbol as $indice => $oPaginasData)
		{
			$arbol[$indice]["subarbol"]=array();
			$arreglopadre[$oPaginasData['datos']->getCodigo()] = $oPaginasData['datos']->getCodigoSuperior();
			$arbol[$indice]["padres"]=$arreglopadre;
			$arreglohijos=array();

			$this->ArregloCodigosHijos($oPaginasData['datos'],$arreglohijos);
			$arbol[$indice]["hijos"]=$arreglohijos;
			if(!$this->ArmarArbolPaginas($oPaginasData['datos'],$arbol[$indice]["subarbol"],$arreglopadre))
				return false;
			unset($arreglopadre[$oPaginasData['datos']->getCodigo()]);	
		}

		return true;
	}
	
	
	
	private function ArregloCodigosHijos($oPaginas,&$arreglohijos)
	{
		$total=0;
		if(!$this->ArregloHijos($oPaginas,$arbol))
			return false;
			
		foreach($arbol as $indice => $oPaginasData)
		{
			if(!$this->ArregloCodigosHijos($oPaginasData['datos'],$arreglohijos))
				return false;
			$arreglohijos[$oPaginasData['datos']->getCodigo()] = $oPaginasData['datos']->getCodigo();
		}
		
		return true;	
	}

	public function CargarVideos(&$oPaginasData,$cantidad=NULL)
	{
			
		$spnombre="sel_pag_paginas_mul_multimedia_xpagcod";
		$sparam=array(
			'ppagcod'=> $oPaginasData->getCodigo(),
			'pmultimediaconjuntocod'=> VIDEOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los videos de la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		

		$videos = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$videos[] = $oMultimedia;
			unset($oMultimedia);
		}

		unset($oMultimediaService);
		$oPaginasData->setVideos($videos);


		return true;	
	}


	public function CargarAudios(&$oPaginasData,$cantidad=NULL)
	{
			
		$spnombre="sel_pag_paginas_mul_multimedia_xpagcod";
		$sparam=array(
			'ppagcod'=> $oPaginasData->getCodigo(),
			'pmultimediaconjuntocod'=> AUDIOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los audios de la pagina.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		

		$audios = array();	
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oMultimedia = new MultimediaData();
			$oMultimediaService->SetData($oMultimedia,$fila);
			$audios[] = $oMultimedia;
			unset($oMultimedia);
		}

		unset($oMultimediaService);
		$oPaginasData->setAudios($audios);


		return true;	
	}


			
}//FIN CLASE
?>