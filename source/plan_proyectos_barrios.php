<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


$comunanumero="";
if (!isset($_POST['barrio']) || $_POST['barrio']=='' || (strlen($_POST['barrio'])>10) || !is_numeric($_POST['barrio']))
{	
	die();
}
$barrio = $_POST['barrio'];
$sql = "SELECT a.*, c.comunanumero FROM  gcba_barrios as a 
		INNER JOIN gcba_comunas_barrios as b on a.barriocod=b.barriocod 
		INNER JOIN gcba_comunas as c on b.comunacod=c.comunacod 
		WHERE a.barriocod=".$barrio." AND barrioestado = 10 group by a.barriocod";
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


?>
<div id="DetallePlanProyectos" class="rounded">
        <div class="panel panel-docs">
            <div class="panel-body">
                <div class="list-group list-group-panel">
                     <div class="list-group-item list-heading list-heading-default">
                      <h3>
                        Comuna N&deg; <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosBarrio['comunanumero'],ENT_QUOTES)?>
                      </h3>  
                        <p class="descriptionList">Listado de proyectos / <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosBarrio['barrionombre'],ENT_QUOTES)?></p>
                     </div>
        
                        <?php 
                            include("plan_proyectos_barrios_lst.php");
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