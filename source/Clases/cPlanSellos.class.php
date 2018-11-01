<?php
class cPlanSellos
{

	function __construct($conexion){
		$this->conexion = &$conexion;
	}

	{
		$archivo = "plan_sellos_".$datos['sellocod'].".json";
		if(file_exists(PUBLICA."json/sellos/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/sellos/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson[$datos['sellocod']]);
			return $array;
		}
		else
			return false;
	}



	{
		$archivo = "plan_sellos.json";
		if(file_exists(PUBLICA."json/sellos/".$archivo))
		{
			$string = file_get_contents(PUBLICA."json/sellos/".$archivo);
			$arrayJson = json_decode($string,true);
			$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
			return $array;
		}
		else
			return false;
	}





?>