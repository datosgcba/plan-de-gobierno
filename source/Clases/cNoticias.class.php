<?
include(DIR_CLASES_DB."cNoticias.db.php");
//----------------------------------------------------------------------------------------- 
//----------------------------------------------------------------------------------------- 
// Clase con la lógica para el manejo de las noticias 

class cNoticias extends cNoticiasdb	 
{
	protected $conexion;

	
	// Constructor de la clase
	public function __construct($conexion){
		$this->conexion = &$conexion;
    } 
	
	// Destructor de la clase
	public function __destruct() {	

    } 	



	public function BuscarNoticia($datos,$folder)
	{
		
		$file = PUBLICA."json/noticias/".$folder."/noticia_".$datos['noticiacod'].".json";
		
		if (file_exists($file))
		{
			$archivo = file_get_contents($file);
			
			$datosnoticia = json_decode($archivo,1);
			
			return $datosnoticia;
		}else
		{
			
			if (!parent::BuscarNoticiaPublicadaxCodigo($datos,$resultado,$numfilas))
				die;
			
			if ($numfilas!=1)
				return false;
				
			$datosnoticia = $this->conexion->ObtenerSiguienteRegistro($resultado);
			
			//armo array de fotos, videos, etc
			
			if (!$this->GenerarArrayDatosJson($datosnoticia))
			return false;
			
			//devuelvo el array
			return $datosnoticia;	
		}
	
	}
	

	public function BuscarNoticiaPrevisualizacion($datos)
	{
		if (!parent::BuscarxCodigo($datos,$resultado,$numfilas))
				die;
				
		if ($numfilas!=1)
			return false;
			
		$datosnoticia = $this->conexion->ObtenerSiguienteRegistro($resultado);
		
		
		if (!$this->GenerarArrayDatosJson($datosnoticia))
			return false;

		//armo array de fotos, videos, etc
			
		//devuelvo el array
		return FuncionesPHPLocal::DecodificarUtf8($datosnoticia);		
	}
	

	
	
	public function GenerarArrayDatosJson(&$array)
	{		
		$noticiacod = $array['noticiacod'];	
		$array['noticiaurl'] = "";
		$array['noticiaurlcompartir'] = "cn".$noticiacod;
		
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_TAGS
		$datosbuscar['noticiacod'] = $noticiacod;
		if (!parent::BuscarTagsxNoticia($datosbuscar,$resultadotags,$numfilastags))
			die;	
		$array['tags'] = array();
		
		if($numfilastags>0){
			$i =0;
			while ($arraytagsinsertar = $this->conexion->ObtenerSiguienteRegistro($resultadotags))
			{
				$array['tags'][$i]=$arraytagsinsertar['noticiatag'];
				$i++;
			}
 		 }	
		 
		 $array['noticiatags'] ="";
		 if(count($array['tags'])>0)
		 	$array['noticiatags'] = implode(" ",$array['tags']);
		 
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_TEMAS
		$DatosBuscarTemasNoticia['noticiacod'] = $noticiacod;
		$DatosBuscarTemasNoticia['temaestado'] = "10";		
		if (!parent::BuscarNoticiasTemasxNoticia($DatosBuscarTemasNoticia,$resultadotemas,$numfilastemas))
			die;	
		$array['temas'] = array();
		if($numfilastemas>0){
			while ($arraytemasinsertar = $this->conexion->ObtenerSiguienteRegistro($resultadotemas))
				$array['temas'][$arraytemasinsertar['temacod']]=$arraytemasinsertar;
 		 }	
		 
		 //BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_MUL_MULTIMEDIAS
		// FOTOS DE LA NOTICIA
		$array['multimedias']['fotos'] = array();
		$datosmultimedia['multimediaconjuntocod'] = FOTOS;
		$datosmultimedia['noticiacod'] = $noticiacod;
		if(!parent::BuscarMultimediaxCodigoNoticiaxMultimediaConjunto($datosmultimedia,$resultadoFotos,$numfilasFotos))
			die;
		
		
		if($numfilasFotos>0)
		{
			while($filaFotos= $this->conexion->ObtenerSiguienteRegistro($resultadoFotos))
			{
				
				if(!$this->GenerarDatosMultimedia($filaFotos,$array['multimedias'],'multimediacod','not'))
					die;
			}
		}
		
		// AUDIOS DE LA NOTICIA	
		$array['multimedias']['audios'] = array();
		$datosmultimedia['multimediaconjuntocod'] = AUDIOS;
		$datosmultimedia['noticiacod'] = $noticiacod;
		if(!parent::BuscarMultimediaxCodigoNoticiaxMultimediaConjunto($datosmultimedia,$resultadoAudios,$numfilasAudios))
			die;
		if($numfilasAudios>0)
		{
			while($filaAudios= $this->conexion->ObtenerSiguienteRegistro($resultadoAudios))
			{
				if(!$this->GenerarDatosMultimedia($filaAudios,$array['multimedias'],'multimediacod','not'))
					die;			
			}
		}
		
		
		// VIDEOS DE LA NOTICIA	
		$array['multimedias']['videos'] = array();
		$datosmultimedia['multimediaconjuntocod'] = VIDEOS;
		if(!parent::BuscarMultimediaxCodigoNoticiaxMultimediaConjunto($datosmultimedia,$resultadoVideos,$numfilasVideos))
			die;
		if($numfilasVideos>0)
		{
			while($filaVideos= $this->conexion->ObtenerSiguienteRegistro($resultadoVideos))
			{
				if(!$this->GenerarDatosMultimedia($filaVideos,$array['multimedias'],'multimediacod','not'))
					die;			
			}
		}
		// FILES DE LA NOTICIA	
		$array['multimedias']['archivos'] = array();
		$datosmultimedia['multimediaconjuntocod'] = FILES;
		if(!parent::BuscarMultimediaxCodigoNoticiaxMultimediaConjunto($datosmultimedia,$resultadoFiles,$numfilasFiles))
			die;
		if($numfilasFiles>0)
		{
			while($filaFiles= $this->conexion->ObtenerSiguienteRegistro($resultadoFiles))
			{
				if(!$this->GenerarDatosMultimedia($filaFiles,$array['multimedias'],'multimediacod','not'))
					die;			
			}
		}
		
		
		//BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_GAL_GALERIAS			
		$arraygalerias = array();
		if (!parent::BuscarGaleriasRelacionadasxNoticia($datosbuscar,$resultadogaleriasrel,$numfilasgaleriasrel))
			die;				
		
		if($numfilasgaleriasrel>0)
		{
			while ($datosgaleriarelacionada = $this->conexion->ObtenerSiguienteRegistro($resultadogaleriasrel))
			{
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriacodigo'] = $datosgaleriarelacionada['galeriacod'];
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriatitulo'] = $datosgaleriarelacionada['galeriatitulo'];
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriadesc'] = $datosgaleriarelacionada['galeriadesc'];
				$arraygalerias[$datosgaleriarelacionada['galeriacod']]['galeriaimportante'] = $datosgaleriarelacionada['galeriaimportante'];
				$arraytemp = array();
				switch ($datosgaleriarelacionada['multimediaconjuntocod'])
				{
					case FOTOS:
					
							
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['fotos'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
					case VIDEOS:
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							if(isset($datosgaleriarelacionada['multimediaidexterno']) && $datosgaleriarelacionada['multimediaidexterno']!="")
								$arraytemp['url'] = "";
							else
								$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['video'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
					case AUDIOS:
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							if(isset($datosgaleriarelacionada['multimediaidexterno']) && $datosgaleriarelacionada['multimediaidexterno']!="")
								$arraytemp['url'] = "";
							else
								$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['audio'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
					case FILES:
							$arraytemp['codigo'] = $datosgaleriarelacionada['multimediacod'];
							$arraytemp['conjunto'] = $datosgaleriarelacionada['multimediaconjuntocod'];
							$arraytemp['titulo'] = $datosgaleriarelacionada['multimediatitulo'];
							$arraytemp['descripcion'] = $datosgaleriarelacionada['multimedianombre'];
							$arraytemp['idexterno'] = $datosgaleriarelacionada['multimediaidexterno'];
							if(isset($datosgaleriarelacionada['multimediaidexterno']) && $datosgaleriarelacionada['multimediaidexterno']!="")
								$arraytemp['url'] = "";
							else
								$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ARCHIVOS.$datosgaleriarelacionada['multimediaubic'];
							if(isset($datosgaleriarelacionada['previewubic']) && $datosgaleriarelacionada['previewubic']!="")
							$arraytemp['multimedias']['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$datosgaleriarelacionada['previewubic'];
							$arraygalerias[$datosgaleriarelacionada['galeriacod']]['multimedias']['archivo'][$datosgaleriarelacionada['multimediacod']] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
						break;
				}
			}
		}
		$array['galerias'] = $arraygalerias;
		

		//BUSCO E INSERTO LOS DATOS DE LA TABLA NOT_NOTICIAS_NOT_NOTICIAS	
		$arrayrelacionadas = array();	
		if (!parent::BuscarNoticiasRelacionadasxCodigoNoticia($datosbuscar,$resultadonoticiasrel,$numfilasnoticiasrel))
			die;		
		
		if($numfilasnoticiasrel>0){
			while ($arrayrelacionada = $this->conexion->ObtenerSiguienteRegistro($resultadonoticiasrel)){
				
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiacod']=$arrayrelacionada['noticiacodrel'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiatitulo']=$arrayrelacionada['noticiatitulo'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiatitulocorto']=$arrayrelacionada['noticiatitulocorto'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiacopete']=$arrayrelacionada['noticiacopete'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiafecha']=$arrayrelacionada['noticiafecha'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiadominio']=$arrayrelacionada['noticiadominio'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiaurl']=$arrayrelacionada['catdominio']."/".$arrayrelacionada['noticiadominio'];;
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiaorden']=$arrayrelacionada['noticiaorden'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['noticiaimportante']=$arrayrelacionada['noticiaimportante'];
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['fotos']= array();
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['videos']= array();
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['audios']= array();
				$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias']['archivos']= array();
			
				$datosbuscarmultimedia['noticiacod'] = $arrayrelacionada['noticiacodrel'];
				if (!parent::BuscarMultimediaxCodigoNoticiaxMinimoOrden($datosbuscarmultimedia,$resultadomultimediarel,$numfilasmultimrdiarel))
					rdie;				
				while ($datosmultimediarel = $this->conexion->ObtenerSiguienteRegistro($resultadomultimediarel)){
						
						if(!$this->GenerarDatosMultimedia($datosmultimediarel,$arrayrelacionadas[$arrayrelacionada['noticiacodrel']]['multimedias'],'multimediacod','not'))
							die;
						
				}
				
			}
		}	
		$array['relacionadas'] = $arrayrelacionadas;
		
		return true;
	}
	
	public function GenerarDatosMultimedia($fila,&$arraymultimedia,$id,$prefijo)
	{
		$arraytemp = array();
		switch ($fila['multimediaconjuntocod'])
		{
			case FOTOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['fotos'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;	
			case VIDEOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";	
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."videos/".$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['videos'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;
			case AUDIOS:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."audios/".$fila['multimediaubic'];	
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['audios'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;
			case FILES:
					$arraytemp['codigo'] = $fila[$id];
					$arraytemp['conjunto'] = $fila['multimediaconjuntocod'];
					$arraytemp['tipo'] = $fila['multimediatipocod'];
					$arraytemp['titulo'] = $fila[$prefijo.'multimediatitulo'];
					$arraytemp['descripcion'] = $fila[$prefijo.'multimediadesc'];
					$arraytemp['nombre_archivo'] = $fila['multimedianombre'];
					$arraytemp['orden'] = $fila[$prefijo.'multimediaorden'];
					$arraytemp['muestrahome'] = $fila[$prefijo.'multimediamuestrahome'];
					$arraytemp['idexterno'] = $fila['multimediaidexterno'];
					if(isset($fila['multimediaidexterno']) && $fila['multimediaidexterno']!="")
						$arraytemp['url'] = "";
					else
						$arraytemp['url'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS."archivos/".$fila['multimediaubic'];
					if(isset($fila['previewubic']) && $fila['previewubic']!="")
					$arraytemp['url_preview'] = CARPETA_SERVIDOR_MULTIMEDIA_NOTICIAS.CARPETA_SERVIDOR_MULTIMEDIA_ORIGINALES.$fila['previewubic'];
					$arraymultimedia['archivos'][$fila[$id]] = FuncionesPHPLocal::DecodificarUtf8($arraytemp);
				break;
		}
		return true;
	}
	
	
	
	
	

	public function CargarRelacionadas(&$oNoticiasData)
	{
		$spnombre="sel_not_noticias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo()
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$relacionadas = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$oNoticiaRelacionada = new NoticiasData();
			$this->SetData($oNoticiaRelacionada,$fila);
			$oNoticiaRelacionada->setNoticiaImportante($fila['noticiaimportante']);
			$relacionadas[] = $oNoticiaRelacionada;
			unset($oNoticiaRelacionada);
		}
		$oNoticiasData->setRelacionadas($relacionadas);
		
		return true;	
	}
	
	
	public function CargarImagenes(&$oNoticiasData,$cantidad=NULL)
	{
			
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData['noticiacod'],
			'pmultimediaconjuntocod'=> FOTOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las imagenes de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$imagenes = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{
			$imagenes[] = $fila;
		}

		return $imagenes;	
	}




	public function CargarGalerias(&$oNoticiasData)
	{
		$spnombre="sel_not_noticias_gal_galerias_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pgaleriaestadocod'=> 10
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las galerias relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
			

		$ArregloGalerias = array();	
		$oGaleriaService = new cGalerias($this->conexion);
		$oMultimediaService = new cMultimedia($this->conexion);
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oGaleriasData = new GaleriasData();
			$oMultimediaData = new MultimediaData();
			$oGaleriaService->SetData($oGaleriasData,$fila);
			$oMultimediaService->SetData($oMultimediaData,$fila);
			$oGaleriasData->SetMultimedia($oMultimediaData);
			$ArregloGalerias[] = $oGaleriasData;
			unset($oMultimediaData);
			unset($oGaleriasData);
		}
		unset($oGaleriaService);
		unset($oMultimediaService);
		$oNoticiasData->setGalerias($ArregloGalerias);
		
		return true;	
	}


	public function getUltimasNoticias($datos)
	{
		$spnombre="sel_not_noticias_publicadas_busqueda";
		$sparam=array(
			'pxcatcod'=>0,
			'pcatcod'=> "-1",
			'porderby'=> "noticiafecha DESC",
			'plimit'=> ""
			);
		if (isset($datos['catcod']) && $datos['catcod']!="")
		{	
			$sparam['pxcatcod'] = 1;
			$sparam['pcatcod'] = $datos['catcod'];
		}
		if (isset($datos['orderby']) && $datos['orderby']!="")
			$sparam['porderby'] = $datos['orderby'];
		if (isset($datos['limit']) && $datos['limit']!="")
			$sparam['plimit'] = $datos['limit'];
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las galerias relacionadas.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$UltimasNoticias = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$UltimasNoticias[] = $oNoticia;
			unset($oNoticia);
		}

		return $UltimasNoticias;	
	}





	/*--------------------------------------------------------------------------------------------------*/
	/*FUNCIONES DE BUSQUEDA DE NOTICIAS*/
	/*--------------------------------------------------------------------------------------------------*/

	public function BusquedaNoticiasporTag($termino,&$CantidadTotal,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xtag";
		$sparam=array(
			'pfields'=> "COUNT(a.noticiacod) as total",
			'pnoticiatag'=> $termino,
			'porderby'=> "a.noticiafecha DESC",
			'plimit'=> ""
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$CantidadTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
		}
		$sparam['pfields'] = "a.noticiatitulo, a.noticiacopete,  a.catdominio, a.noticiadominio, a.noticiafecha, a.noticiatitulocorto, a.noticiavolanta, a.noticiatags";
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloNoticiasTag = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$ArregloNoticiasTag[] = $oNoticia;
			unset($oNoticia);
		}

		return $ArregloNoticiasTag;	
		
	}



	public function BusquedaNoticias($termino,&$CantidadTotal,$limit="")
	{
		if(strlen(trim($termino))<3)
			return $this->BusquedaNoticiasLike($termino,$limit="");
		
		$palabras = preg_split('/ /',trim($termino));
		$palabrasarreglo = implode(',',$palabras);
		$consultapalabra = " >".trim($termino)." <".trim($termino)."*";
		foreach($palabras as $valor) { 
				$consultapalabra .= " >".$valor." <".$valor."*";
		} 	
		$cantidadTotal = $this->BusquedaCantidadNoticias($consultapalabra);

		$spnombre="sel_not_noticias_publicadas_xbusqueda";
		$sparam=array(
			'pterm'=> $consultapalabra,
			'plimit'=> ""
			);
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloBusqueda = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$ArregloBusqueda[] = $oNoticia;
			unset($oNoticia);
		}

		return $ArregloBusqueda;	
		
	}
	


	private function BusquedaCantidadNoticias($termino,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xbusqueda_cantidad";
		$sparam=array(
			'pterm'=> $termino
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la cantida de noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		$cantidad = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$cantidad = $datosTotales['total'];	
		}

		return $cantidad;	
		
	}
	

	private function BusquedaNoticiasLike($termino,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xbusqueda_like";
		$sparam=array(
			'pfields'=> "COUNT(a.noticiacod) as total",
			'pterm'=> $termino,
			'plimit'=> ""
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$busqueda['cantidadTotal'] = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$busqueda['cantidadTotal'] = $datosTotales['total'];	
		}
		$sparam['pfields'] = "DISTINCT(a.noticiacod) as noticiacod, a.catdominio, a.catnom, a.catcod, a.noticiatitulo, a.noticiacopete, a.noticiadominio, a.noticiafecha, a.noticiatitulocorto, a.noticiavolanta";
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		$ArregloBusqueda = array();	
		while ($fila = $this->conexion->ObtenerSiguienteRegistro($resultado))	
		{	
			$oNoticia = new NoticiasData();
			$this->SetData($oNoticia,$fila);
			$ArregloBusqueda[] = $oNoticia;
			unset($oNoticia);
		}


		return $ArregloBusqueda;	
		
	}
	

	public function CargarVideos(&$oNoticiasData,$cantidad=NULL)
	{
			
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pmultimediaconjuntocod'=> VIDEOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los videos de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
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
		$oNoticiasData->setVideos($videos);


		return true;	
	}


	public function CargarAudios(&$oNoticiasData,$cantidad=NULL)
	{
			
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $oNoticiasData->getCodigo(),
			'pmultimediaconjuntocod'=> AUDIOS,
			'plimit'=>""
			);
			
		if ($cantidad!=NULL)
			$sparam['plimit'] = "Limit 0,".$cantidad;
			
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los audios de la noticia.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
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
		$oNoticiasData->setAudios($audios);


		return true;	
	}



	public function BusquedaNoticiasporTema($datos,&$CantidadTotal,&$resultado,$limit="")
	{
		$spnombre="sel_not_noticias_publicadas_xtema";
		$sparam=array(
			'pfields'=> "COUNT(notpub.noticiacod) as total",
			'ptemacod'=> $datos['temacod'],
			'porderby'=> "notpub.noticiafecha DESC",
			'plimit'=> ""
			);
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}
		
		$CantidadTotal = 0;
		if ($numfilas>0)
		{
			$datosTotales = $this->conexion->ObtenerSiguienteRegistro($resultado);
			$CantidadTotal = $datosTotales['total'];	
		}
		$sparam['pfields'] = "notpub.*, c.multimediadesc, c.multimedianombre, c.multimediaubic, mc.multimediacatcarpeta, mulVid.multimediaidexterno, mulVid.multimediatipocod AS tipovideo";
		if ($limit!="")
			$sparam['plimit'] = $limit;

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__));
			return false;
		}

		return true;	
		
	}
	
	
			public function SetData(&$oNoticiasData,$datosnoticia)
	{
		if (isset($datosnoticia['noticiacod']))
			$oNoticiasData->setCodigo($datosnoticia['noticiacod']);
		
		if (isset($datosnoticia['catcod']))
			$oNoticiasData->setCodigoCategoria($datosnoticia['catcod']);
		
		if (isset($datosnoticia['catdominio']))
			$oNoticiasData->setDominioCategoria($datosnoticia['catdominio']);
		
		if (isset($datosnoticia['catnom']))
			$oNoticiasData->setNombreCategoria(htmlspecialchars($datosnoticia['catnom'],ENT_QUOTES));
		
		if (isset($datosnoticia['noticiatitulo']))
			$oNoticiasData->setTitulo( FuncionesPHPLocal::HtmlspecialcharsBigtree($datosnoticia['noticiatitulo'],ENT_QUOTES));
		
		if (isset($datosnoticia['noticiadominio']))
			$oNoticiasData->setDominio($datosnoticia['noticiadominio']);
		
		if (isset($datosnoticia['noticiatitulo']))
			$oNoticiasData->setTituloCorto(htmlspecialchars($datosnoticia['noticiatitulo'],ENT_QUOTES));
		
		if (isset($datosnoticia['noticiahrefexterno']))
			$oNoticiasData->setHrefExterno($datosnoticia['noticiahrefexterno']);
		
		if (isset($datosnoticia['noticiacopete']))
			$oNoticiasData->setCopete($datosnoticia['noticiacopete']);
		
		if (isset($datosnoticia['noticiacuerpoprocesado']))
			$oNoticiasData->setCuerpo($datosnoticia['noticiacuerpoprocesado']);
		
		if (isset($datosnoticia['noticiavolanta']))
			$oNoticiasData->setVolanta(htmlspecialchars($datosnoticia['noticiavolanta'],ENT_QUOTES));
		
		if (isset($datosnoticia['noticiaautor']))
			$oNoticiasData->setAutor(htmlspecialchars($datosnoticia['noticiaautor'],ENT_QUOTES));
		
		if (isset($datosnoticia['noticiatags']))
			$oNoticiasData->setTags(htmlspecialchars($datosnoticia['noticiatags'],ENT_QUOTES));
		
		if (isset($datosnoticia['noticiafecha']))
			$oNoticiasData->setFecha($datosnoticia['noticiafecha']);
		
		if (isset($datosnoticia['noticialat']))
			$oNoticiasData->setLatitud($datosnoticia['noticialat']);
		
		if (isset($datosnoticia['noticialng']))
			$oNoticiasData->setLongitud($datosnoticia['noticialng']);
		
		if (isset($datosnoticia['noticiazoom']))
			$oNoticiasData->setMapaZoom($datosnoticia['noticiazoom']);
		
		if (isset($datosnoticia['noticiatype']))
			$oNoticiasData->setMapaTipo($datosnoticia['noticiatype']);
		
		if (isset($datosnoticia['noticiadireccion']))
			$oNoticiasData->setDireccion($datosnoticia['noticiadireccion']);
		
		if (isset($datosnoticia['noticiamuestramapa']))
			$oNoticiasData->setMuestraMapa($datosnoticia['noticiamuestramapa']);
		
		if (isset($datosnoticia['noticiacomentarios']))
			$oNoticiasData->setTieneComentarios($datosnoticia['noticiacomentarios']);
		

		if (isset($datosnoticia['noticiacod']))
		{
			$dominiocompartir = DOMINIOWEB."cn".$datosnoticia['noticiacod'];
			$oNoticiasData->setDominioCompartir($dominiocompartir);
		}
		
		return true;
	}
			
}//FIN CLASE
?>