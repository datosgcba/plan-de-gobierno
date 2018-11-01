<?php
class cPlanTagsCategorias
{

	function __construct($conexion){
		$this->conexion = &$conexion;
	}

	{
		$archivo = "plan_tags_categorias_".$datos['plantagcatcod'].".json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['plantagcatcod']]);
			return $array;
		}
		else
			return false;
	}



	{
		$archivo = "plan_tags_categorias.json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
			return $array;
		}
		else
			return false;
	}





?>