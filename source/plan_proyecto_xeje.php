<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


$planobjetivocod="";
if (!isset($_POST['objetivo']) || $_POST['objetivo']=='' || (strlen($_POST['objetivo'])>10) || !is_numeric($_POST['objetivo']))
{	
	die();
}
$planobjetivocod = $_POST['objetivo'];
$eje="";
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



$comunacod="";

$sql = "SELECT * FROM  plan_objetivos   WHERE planobjetivocod=".$planobjetivocod." AND planobjetivoestado=10 ";
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
if ($cantidad!=1)
	die();
$datosObjetivos = $conexion->ObtenerSiguienteRegistro($resultado);


?>
<div id="DetallePlanProyectos" class="rounded">
        <div class="panel panel-docs">
            <div class="panel-body">
                <div class="list-group list-group-panel">
                     <div class="list-group-item list-heading list-heading-default">
                      <div class="col-md-9">
                      
                      <h3 style="color:<? echo $datosPlan['planejecolor']?>">
                        <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosPlan['planejenombre'],ENT_QUOTES)?>&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true">&nbsp;</i><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosObjetivos['planobjetivonombre'],ENT_QUOTES)?>
                      </h3>
                      <p class="descriptionList">Listado de proyectos</p>
                      </div>
                      <div class="col-md-3">
                      	<div class="btn-volver">
                        	<a href="javascript:void(0)" onclick="LoadObjetivosxEje(this,<? echo $planejecod?>)" class="btn btn-primary">Volver a los objetivos</a>
                      	</div>
                      </div>
                     </div>
        
                        <?php 
                            include("plan_proyecto_xeje_lst.php");
                        ?>
                </div>
                <div class="text-right">
                    <a href="javascipt:void(0)" class="Subir btn btn-primary">Ir arriba&nbsp;<i class="fa fa-chevron-up" aria-hidden="true"></i></a>
                </div>  
       		</div>
       </div>
       <div class="clearboth"></div>     
</div>
<?php  
?>