<? 
abstract class cNoticiasdb
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
	protected function BuscarNoticiaPublicadaxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_publicadas_xcodigo";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarxCodigo($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_xcodigo";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarTagsxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_tags_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);
	
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar la noticia por código.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;
	}
	
	
	protected function BuscarNoticiasTemasxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_temas_xnoticiacod";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod'],
			'ptemaestado'=> $datos['temaestado']
			);

		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno))
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al obtener los temas por noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}

		return true;
	
	}
	
	
	protected function BuscarMultimediaxCodigoNoticiaxMultimediaConjunto($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod_mulconjuntocod";
		$sparam=array(
			'pnoticiacod' => $datos['noticiacod'],
			'pmultimediaconjuntocod' => $datos['multimediaconjuntocod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar los archivos multimedia de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarGaleriasRelacionadasxNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_gal_galerias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las galerias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	
	
	protected function BuscarNoticiasRelacionadasxCodigoNoticia($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_not_noticias_relacionadas_xcodigonoticia";
		$sparam=array(
			'pnoticiacod'=> $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,"Error al buscar las noticias relacionadas. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>""));
			return false;
		}
		
		return true;	
	}
	
	protected function BuscarMultimediaxCodigoNoticiaxMinimoOrden($datos,&$resultado,&$numfilas)
	{
		$spnombre="sel_not_noticias_mul_multimedia_xnoticiacod_minimoorden";
		$sparam=array(
			'pnoticiacod' => $datos['noticiacod']
			);		
			
		if(!$this->conexion->ejecutarStoredProcedure($spnombre,$sparam,$resultado,$numfilas,$errno) )
		{
			FuncionesPHPLocal::MostrarMensaje($this->conexion,MSG_ERRGRAVE,"Error al buscar los archivos multimedia de la noticia. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>$this->formato));
			return false;
		}
		
		return true;	
	}

	

}
?>