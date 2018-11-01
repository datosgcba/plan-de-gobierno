<?php  
if (isset($_GET['reload']) && $_GET['reload']==1)
{
	include("./config/include.php");
	
	$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
	$conexion->SeleccionBD(BASEDATOS);
	
	FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));
	
	
	$planejecod="";
	if (!isset($_POST['eje']) || $_POST['eje']!='' || (strlen($_POST['eje'])>10) || !is_numeric($_POST['eje']))
	{	
		FuncionesPHPLocal::Error404();
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
}
//busco los sellos y los pongo en un array
$sql = "SELECT a.*, b.multimediaubic FROM plan_sellos a INNER JOIN mul_multimedia AS b ON a.multimediacod=b.multimediacod WHERE a.selloestado = 10";
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultadoSellos,$errno);
$arraysellos = array();
while($sellos = $conexion->ObtenerSiguienteRegistro($resultadoSellos))
	$arraysellos[$sellos['sellocod']] = $sellos;

$sql = "SELECT a.planobjetivocod, a.planobjetivonombre, a.planobjetivosellos
FROM plan_objetivos AS a 
INNER JOIN plan_proyectos AS b ON a.planobjetivocod=b.planobjetivocod 
INNER JOIN plan_proyectos_ejes AS c ON b.planproyectocod=c.planproyectocod 
WHERE c.planejecod=".$planejecod." AND planobjetivoestado=10 
GROUP BY a.planobjetivocod ORDER BY planobjetivonombre";

$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);

$cantidadEncontrados = $conexion->ObtenerCantidadDeRegistros($resultado);


	if ($cantidadEncontrados==0)
	{
		?>
			<div class="alert alert-primary">
				<strong>Sin objetivos cargados.</strong>
			</div>
		<? 
	}else
	{
		?>
        
        <? 
		while($fila = $conexion->ObtenerSiguienteRegistro($resultado)){?>
            <div  class="list-group-item">
                <a href="javascript:void(0)" class="col-md-9" onclick="LoadProyectosxObjetivo(this,<? echo $fila['planobjetivocod']?>,<? echo $planejecod?>)" data-original-title="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planobjetivonombre'],ENT_QUOTES)?>" title="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planobjetivonombre'],ENT_QUOTES)?>">
                  <h4><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['planobjetivonombre'],ENT_QUOTES)?></h4>
                </a>
                <div class="col-md-3">
                    <? 
						$planobjetivosellos = explode(",",$fila['planobjetivosellos']);
						foreach($planobjetivosellos as $sellos)
						{
							if(array_key_exists($sellos,$arraysellos))
							{
								?>						
                            	<img src="/multimedia/noticias/N/<? echo $arraysellos[$sellos]['multimediaubic']; ?>" title="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($arraysellos[$sellos]['sellonombre'],ENT_QUOTES); ?>"/>
							<? }
						}
					?>
                </div>
            </div>
		<?php      
		}?>
        <? 
	}
?>