<?php  
if (isset($_GET['reload']) && $_GET['reload']==1)
{
	include("./config/include.php");
	
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
	$comunanumero="";
	if (!isset($_POST['barrio']) || $_POST['barrio']=='' || (strlen($_POST['barrio'])>10) || !is_numeric($_POST['barrio']))
	{	
		die();
	}
	
	$barrio = $_POST['barrio'];
	$sql = "SELECT * FROM  gcba_barrios  WHERE barriocod=".$barrio." AND barrioestado = 10";
	$erroren = "";
	$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
	$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
	if ($cantidad!=1)
	{
		?>
			<div class="alert alert-primary">
				<strong>Sin proyectos asociados.</strong>
			</div>
		<? 
		die();
	}
	$datosBarrio = $conexion->ObtenerSiguienteRegistro($resultado);
}



$sql = "SELECT a.* FROM plan_proyectos AS a 
INNER JOIN plan_proyectos_barrios AS b ON a.planproyectocod=b.planproyectocod  
INNER JOIN gcba_barrios AS c ON b.barriocod=c.barriocod  
WHERE b.barriocod=".$barrio;
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
              <div class="col-md-9 col-sm-10 col-xs-8">
              		<h4><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planproyectonombre'],ENT_QUOTES)?></h4>
              </div>
              <div class="col-md-3 col-sm-2 col-xs-4 shortcut-xssmall">
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