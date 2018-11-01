<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


$comunacod="";
$planejecod="";
$nombre="";
$tag="";
if (!isset($_POST['eje']) || $_POST['eje']=='' || (strlen($_POST['eje'])>10) || !is_numeric($_POST['eje']))
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


?>
<div id="DetallePlanProyectos" class="rounded">
        
        
        <div class="panel panel-docs">
            <div class="panel-body">
                <div class="list-group list-group-panel">
                     <div class="list-group-item list-heading list-heading-default">
                      <h3 style="color:<? echo $datosPlan['planejecolor']?>">
                        <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosPlan['planejenombre'],ENT_QUOTES)?>
                      </h3>
                        <p class="descriptionList">Listado de objetivos</p>
                     </div>
        
                        <?php 
                            include("plan_proyectos_objetivos_lst.php");
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