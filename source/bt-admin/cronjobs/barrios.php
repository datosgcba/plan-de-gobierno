<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
set_time_limit ( 600000000 );
ini_set('memory_limit', '512M');
error_reporting(E_WARNING | E_ERROR);
include("../config/include.php");
$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);


$sql = "select * from gcba_barrios order by barrionombre ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$vecBarrios[strtolower(trim($fila["barrionombre"]))]=$fila["barriocod"];
}

$sql = "select * from proyectos_tmp where barrios<>'' and barrios<>'#N/A' and barrios is not null order by barrios ASC";
$erroren="";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$vecBarriosExcel=array();
$cantbarrios=0;
while($fila = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$barrios=explode(",", $fila["barrios"]);
	foreach($barrios as $barrio)
	{
		$nombre=strtolower(trim($barrio));
		$sql2 = "insert into plan_proyectos_barrios (`planproyectocod`, `barriocod`, `ultmodfecha`, `ultmodusuario`) values ( '".$fila["id"]."','".$vecBarrios[$nombre]."', now(),1);";
		echo $sql2."<br>";
		$erroren="";
		$conexion->_EjecutarQuery($sql2,$erroren,$resultadoptags,$errno);
		if (!isset($vecBarriosExcel[$nombre]))
		{
			$vecBarriosExcel[$nombre]=1;
		}else
			$vecBarriosExcel[$nombre]++;
		
	}
}
print_r($vecBarriosExcel);


?>