<?php  
include("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("multimedia"=>"si"));

$oEncabezados = new cEncabezados($conexion);


$id="";
if (!isset($_GET['id']) || $_GET['id']=='' || (strlen($_GET['id'])>10) || !is_numeric($_GET['id']))
{	
	FuncionesPHPLocal::Error404();
	die();
}
$planproyectocod = $_GET['id'];



$sql = "SELECT  a.*, c.planproyectoestadonombre, c.planproyectoestadocolor, d.planjurisdiccionnombre, GROUP_CONCAT(distinct plantagnombre SEPARATOR ', ') AS tags, 
GROUP_CONCAT(distinct planejenombre SEPARATOR ', ') AS ejenombre, i.planobjetivonombre 
,GROUP_CONCAT(distinct h.planejeconstante SEPARATOR ', ') AS ejeconstante
FROM  plan_proyectos as a INNER JOIN 
plan_proyectos_ejes AS b ON a.planproyectocod=b.planproyectocod 
INNER JOIN plan_proyectos_estados AS c ON a.planproyectoestadocod=c.planproyectoestadocod  
INNER JOIN plan_jurisdicciones AS d ON a.planjurisdiccioncod=d.planjurisdiccioncod  
LEFT JOIN plan_proyectos_comunas AS e ON a.planproyectocod=e.planproyectocod  
LEFT JOIN plan_proyectos_tags AS f ON a.planproyectocod=f.planproyectocod  
LEFT JOIN plan_tags AS g ON f.plantagcod=g.plantagcod  
LEFT JOIN plan_ejes AS h ON b.planejecod=h.planejecod 
LEFT JOIN plan_objetivos AS i ON a.planobjetivocod=i.planobjetivocod 
 WHERE a.planproyectocod=".$planproyectocod;
$erroren = "";
$conexion->_EjecutarQuery($sql,$erroren,$resultado,$errno);
$cantidad = $conexion->ObtenerCantidadDeRegistros($resultado);
if ($cantidad!=1)
	die();
$datosProyecto = $conexion->ObtenerSiguienteRegistro($resultado);
$vecEjes=array();
if ($datosProyecto["ejeconstante"]!="")
{
	$vecEjes=explode(",",$datosProyecto["ejeconstante"]);
}
?>
	<div class="row">
        <div class="col-md-10 col-xs-12">
            <h3>
                <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosProyecto['planproyectonombre'],ENT_QUOTES)?>
            </h3>
            <? /*<h4>ID: #<? echo $datosProyecto['planproyectocod'];?></h4>*/?>
            <h4><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosProyecto['ejenombre'],ENT_QUOTES)?></h4>
            <p>
                <? echo $datosProyecto['planproyectodescripcion']?>
            </p>
		</div>
        <? if (count($vecEjes)>0){?>
        <div class="col-md-2 col-xs-12">
        	<? foreach($vecEjes as $eje){?>
            	<div class="shortcut-small">
            	<img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/<? echo trim($eje)?>.png" title="<? echo trim($eje)?>"/>
                </div>
            <? }?>
            <? if ($datosProyecto['planproyectocompromiso']==1){?>
            	<div class="shortcut-small">
            	<img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/compromiso.png" title="Compromiso de gobierno"/>
                </div>
            <? }?>
            <? if ($datosProyecto['planproyectocompromiso']==2){?>
            	<div class="shortcut-small">
            	<img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/sticker_compromisocumplido.png" title="Compromiso de gobierno"/>
                </div>
            <? }?>
            <? 
			if ($datosProyecto['planproyectobaelige']==1)
				{?>
                    <div class="shortcut-small">
                        <img src="<? echo DOMINIOWEB?>/public/plandegobierno/imagenes/baelige.png" title="BA Elige"/>
                    </div>
			<? }?>
        </div>
        <? }?>
    </div>
    <hr />
	<div class="row">
            <div class="col-md-3 col-xs-12">
                <strong>Desde</strong>
                <div class="clearboth nada"></div>    
                <? echo FuncionesPHPLocal::ConvertirFecha($datosProyecto['planproyectofdesde'],"aaaa-mm-dd","dd/mm/aaaa");?>
            </div>

            <div class="col-md-6 col-xs-12">
                	<strong>Hasta</strong>
                    <div class="clearboth nada"></div>    
                    <? echo FuncionesPHPLocal::ConvertirFecha($datosProyecto['planproyectofhasta'],"aaaa-mm-dd","dd/mm/aaaa");?>
            </div>
            <div class="clearboth brisa_vertical"></div>   
             <div class="col-xs-12">
                 <strong>Area</strong>
                 <div class="clearboth nada"></div> 
                <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosProyecto['planjurisdiccionnombre'],ENT_QUOTES)?>
    		</div>
            <div class="clearboth brisa_vertical"></div>   
             <div class="col-xs-12">
                 <strong>Etiquetas</strong>
                 <div class="clearboth nada"></div> 
                <? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($datosProyecto['tags'],ENT_QUOTES)?>
    		</div>
    <div class="clearboth"></div>     
<?php  
?>