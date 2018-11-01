<?php  
if (isset($_GET['reload']) && $_GET['reload']==1)
{
	include("./config/include.php");
	
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
	$plantagcod="";
	if (!isset($_POST['tag']) || $_POST['tag']=='' || (strlen($_POST['tag'])>10) || !is_numeric($_POST['tag']))
	{	
		die();
	}
	$plantagcod = $_POST['tag'];
	
	$tag = $_POST['tag'];
	$sql = "SELECT * FROM  plan_tags  WHERE plantagcod=".$tag." AND plantagestado = 10";
	$erroren = "";
	$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
	if ($cantidad!=1)
		die();
	$datosTag = $conexion->ObtenerSiguienteRegistro($resultado);
}



$sql = "SELECT a.*, c.planproyectoestadonombre, c.planproyectoestadocolor, d.planjurisdiccionnombre, GROUP_CONCAT(plantagnombre SEPARATOR ', ') AS tags FROM plan_proyectos AS a INNER JOIN 
plan_proyectos_ejes AS b ON a.planproyectocod=b.planproyectocod 
INNER JOIN plan_proyectos_estados AS c ON a.planproyectoestadocod=c.planproyectoestadocod  
INNER JOIN plan_jurisdicciones AS d ON a.planjurisdiccioncod=d.planjurisdiccioncod  
LEFT JOIN plan_proyectos_comunas AS e ON a.planproyectocod=e.planproyectocod  
LEFT JOIN plan_proyectos_tags AS f ON a.planproyectocod=f.planproyectocod  
LEFT JOIN plan_tags AS g ON f.plantagcod=g.plantagcod  
WHERE 1 = 1";

if (trim($plantagcod)!="")
	$sql .= " AND f.plantagcod = ".$plantagcod;
$sql .= " GROUP BY a.planproyectonombre";

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
                <?
                if ($fila['planproyectobaelige']==1)
				{
						?>
						<img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/baelige.png" title="BA Elige"/>
						<?
					}?>
              </div>
            </a>
			<? 	}
	}
?>