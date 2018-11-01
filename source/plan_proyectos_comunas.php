<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


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


?>
<div id="DetallePlanProyectos" class="rounded">
        <div class="panel panel-docs">
            <div class="panel-body">
                <div class="list-group list-group-panel">
                     <div class="list-group-item list-heading list-heading-default">
                      <h3>
                        Comuna N&deg;<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunanumero'],ENT_QUOTES)?>
                      </h3>  
                        <p class="descriptionList">Listado de proyectos / <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosComuna['comunabarrios'],ENT_QUOTES)?></p>
                     </div>
        
                        <?php 
                            include("plan_proyectos_comunas_lst.php");
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