<?php  
if (isset($_GET['reload']) && $_GET['reload']==1)
{
	include("./config/include.php");
	
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
	$comunanumero="";
	if (!isset($_POST['comuna']) || $_POST['comuna']=='' || (strlen($_POST['comuna'])>10) || !is_numeric($_POST['comuna']))
	{	
		die();
	}
	
	$comunanumero = $_POST['comuna'];
	$sql = "SELECT * FROM  gcba_comunas  WHERE comunanumero=".$comunanumero." AND comunaestado = 10";
	$erroren = "";
	$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
	if ($cantidad!=1)
		die();
	$datosComuna = $conexion->ObtenerSiguienteRegistro($resultado);
}



$sql = "SELECT a.* FROM plan_proyectos AS a 
INNER JOIN plan_proyectos_comunas AS b ON a.planproyectocod=b.planproyectocod  
INNER JOIN gcba_comunas AS c ON b.comunacod=c.comunacod  
WHERE 1 = 1";

if (trim($comunanumero)!="")
	$sql .= " AND c.comunanumero = ".$comunanumero;
$sql .= " GROUP BY a.planproyectocod";

$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);

$cantidadEncontrados = $conexion->ObtenerCantidadDeRegistros($resultado);

	if ($cantidadEncontrados==0)
	{
		?>
			<div class="alert alert-primary">
				<strong>Sin proyectos asociados.</strong>
			</div>
		<? 
	}else
	{
		?>
        <? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado)){?>
            <a href="javascript:void(0)" class="list-group-item" onclick="OpenProject(<? echo $fila['planproyectocod']?>)">
              <h4><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planproyectonombre'],ENT_QUOTES)?></h4>
            </a>
			<? 	}
	}
?>