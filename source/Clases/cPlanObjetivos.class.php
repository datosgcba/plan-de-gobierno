<?php
class cPlanObjetivos
{

	function __construct($conexion){
		$this->conexion = &$conexion;
	}

	{
		$archivo = "plan_objetivos_".$datos['planobjetivocod'].".json";
		if(file_exists(PUBLICA."json/Plan/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/Plan/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['planobjetivocod']]);
			return $array;
		}
		else
			return false;
	}



	{
		$archivo = "plan_objetivos.json";
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