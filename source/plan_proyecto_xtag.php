<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


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


?>
<div id="DetallePlanProyectos" class="rounded">
        <div class="panel panel-docs">
            <div class="panel-body">
                <div class="list-group list-group-panel">
                     <div class="list-group-item list-heading list-heading-default">
                      <h3>
                        <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosTag['plantagnombre'],ENT_QUOTES)?>
                      </h3>  
                        <p class="descriptionList">Listado de proyectos</p>
                     </div>
        
                        <?php 
                            include("plan_proyecto_xtag_lst.php");
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