<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
$sql = "SELECT a.plantagcod, a.plantagnombre, COUNT(b.planproyectocod) AS total FROM plan_tags AS a 
		INNER JOIN plan_proyectos_tags AS b ON a.plantagcod=b.plantagcod 
		WHERE a.plantagestado=10 
		GROUP BY a.plantagcod HAVING total>3 ORDER BY total DESC";
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
$arrayComunas = array();
while($datosComuna = $conexion->ObtenerSiguienteRegistro($resultado))
{
	$arrayComunas[$datosComuna['plantagcod']]['codigo'] = $datosComuna['plantagcod'];
	$arrayComunas[$datosComuna['plantagcod']]['text'] = utf8_encode($datosComuna['plantagnombre']);
	$arrayComunas[$datosComuna['plantagcod']]['weight'] = $datosComuna['total'];
	$arrayComunas[$datosComuna['plantagcod']]['link'] = '#tag_'.$datosComuna['plantagcod'];
}

echo json_encode(array_values($arrayComunas));

?>