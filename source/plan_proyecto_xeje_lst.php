<?php  
if (isset($_GET['reload']) && $_GET['reload']==1)
{
	include("./config/include.php");
	
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
	
	$planobjetivocod="";
	if (!isset($_POST['objetivo']) || $_POST['objetivo']=='' || (strlen($_POST['objetivo'])>10) || !is_numeric($_POST['objetivo']))
	{	
		die();
	}
	$planobjetivocod = $_POST['objetivo'];
	
	$planejecod="";
	if (!isset($_POST['eje']) || $_POST['eje']=='' || (strlen($_POST['eje'])>10) || !is_numeric($_POST['eje']))
	{	
		die();
	}
	$planejecod = $_POST['eje'];
	$sql = "SELECT * FROM  plan_ejes  WHERE planejecod=".$planejecod." AND  planejeestado = 10";
	$erroren = "";
	$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
	if ($cantidad!=1)
		die();
	$datosPlan = $conexion->ObtenerSiguienteRegistro($resultado);
	
	$sql = "SELECT * FROM  plan_objetivos  WHERE planobjetivocod=".$planobjetivocod." AND planobjetivoestado=10 ";
	$erroren = "";
	$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
	if ($cantidad!=1)
		die();
	$datosObjetivos = $conexion->ObtenerSiguienteRegistro($resultado);
	$comunacod = "";
}



$sql = "SELECT COUNT(a.planproyectocod) AS total 
FROM plan_proyectos AS a 
INNER JOIN plan_proyectos_ejes AS b ON a.planproyectocod=b.planproyectocod 
INNER JOIN plan_proyectos_estados AS c ON a.planproyectoestadocod=c.planproyectoestadocod  
INNER JOIN plan_jurisdicciones AS d ON a.planjurisdiccioncod=d.planjurisdiccioncod  
LEFT JOIN plan_proyectos_comunas AS e ON a.planproyectocod=e.planproyectocod  
LEFT JOIN plan_proyectos_tags AS f ON a.planproyectocod=f.planproyectocod  
WHERE 1 = 1";
if (trim($planobjetivocod)!="")
	$sql .= " AND a.planobjetivocod = ".$planobjetivocod;
if (trim($planejecod)!="")
	$sql .= " AND b.planejecod = ".$planejecod;

$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$datosTotal = $conexion->ObtenerSiguienteRegistro($resultado);


$cantidadpaginacion = 10000;
$inicio=0;
if (!isset($_POST['pagina']))
	$paginaactual = 1;
else
{
	if(strlen($_POST['pagina'])>10)
		die();
		
	if (!FuncionesPHPLocal::ValidarContenido($conexion,$_POST['pagina'],"NumericoEntero"))
		die();
		
	$inicio = $cantidadpaginacion*($_POST['pagina']-1);
	$paginaactual=$_POST['pagina'];
}



$sql = "SELECT a.*, c.planproyectoestadonombre, c.planproyectoestadocolor, d.planjurisdiccionnombre, GROUP_CONCAT(plantagnombre SEPARATOR ', ') AS tags FROM plan_proyectos AS a INNER JOIN 
plan_proyectos_ejes AS b ON a.planproyectocod=b.planproyectocod 
INNER JOIN plan_proyectos_estados AS c ON a.planproyectoestadocod=c.planproyectoestadocod  
INNER JOIN plan_jurisdicciones AS d ON a.planjurisdiccioncod=d.planjurisdiccioncod  
LEFT JOIN plan_proyectos_comunas AS e ON a.planproyectocod=e.planproyectocod  
LEFT JOIN plan_proyectos_tags AS f ON a.planproyectocod=f.planproyectocod  
LEFT JOIN plan_tags AS g ON f.plantagcod=g.plantagcod  
WHERE 1 = 1";

if (trim($planobjetivocod)!="")
	$sql .= " AND a.planobjetivocod = ".$planobjetivocod;
if (trim($planejecod)!="")
	$sql .= " AND b.planejecod = ".$planejecod;

$sql .= " GROUP BY a.planproyectocod LIMIT ".$inicio.", ".$cantidadpaginacion;
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$cantidadEncontrados = $conexion->ObtenerCantidadDeRegistros($resultado);

FuncionesPHPLocal::ArmarPaginadoFront($cantidadpaginacion,$datosTotal['total'],$paginaactual,$primera,$ultima,$numpages,$current,$TotalSiguiente,$TotalVer);

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
              <div class="col-md-11 col-sm-10 col-xs-8">
              		<h4><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planproyectonombre'],ENT_QUOTES)?></h4>
              </div>
              <div class="col-md-1 col-sm-2 col-xs-4 shortcut-xssmall">
              	<? if ($fila['planproyectocompromiso']>0){
					if ($fila['planproyectocompromiso']==1){
						?>
						<img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/compromiso.png" title="Compromiso de gobierno"/>
						<?
					}else{
						?>
						<img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/sticker_compromisocumplido.png" title="Compromiso de gobierno"/>
						<?					
					}
						
				}?>
              </div>
            </a>
			<? 	} ?>
            <? 
			$class = "disabled";
			$onclick = "";
			if($paginaactual<$numpages)
			{
				$onclick = ' onclick="BuscarProyectos(this,'.($paginaactual+1).')"';
				$class = "";
			}
			?>    
        <? 
	}
?>