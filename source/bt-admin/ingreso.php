<?php  
require('./config/include.php');
require('./Librerias/cGoogleAnalytics.php');

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

// carga las constantes generales
FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA));
$conexion->SetearAdmiGeneral(ADMISITE);

// arma las variables de sesion y verifica si se tiene permisos
$sesion = new Sesion($conexion,false); // Inicia session y no borra
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

// ve si el sistema está bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);



$oUsuariosAccesos = new cUsuariosAccesos($conexion);
if(!$oUsuariosAccesos->BuscarMiUltimoAcceso ($resultultacceso,$numfilasultacceso))
	return false;

$oArchivos = new cArchivos($conexion);
if(!$oArchivos->ObtenerEspacioEnDisco ($datosEspacio))
	return false;

$tieneultacceso = false;
if ($numfilasultacceso>0)
{
	$datosultimoacceso = $conexion->ObtenerSiguienteRegistro($resultultacceso);
	$tieneultacceso = true;
}



?>

        <link href="css/ui.dashboard.css" rel="stylesheet" title="style" media="all" />


        <div class="inner-page-title"> 
          <h2>Panel de control</h2>
          <span>Bienvenido <?php  echo FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['usuarionombre'],ENT_QUOTES)?> <?php  echo FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['usuarioapellido'],ENT_QUOTES)?>!</span> 
        </div>		

        <div id="dashboard-buttons">
            <div class="clear">&nbsp;</div>    
                <div class="col-md-8" style="margin-top:40px">
                        <div class="white-panel-widget clearfix m-sidebar m-bot-30 no-pad">
                            <div class="average-statistics-wrapper row no-margin">
                                <h3>Plan de Gobierno</h3>
                            </div>
                        	<div class="wrapper-circle-charts">
                                <div class="wrp row no-margin">
                                <?php  
								$total="";
								//proyectos abiertos
								$oProyectos = new cPlanProyectos($conexion);
								$datos['planproyectoestadocod'] = "1,2,3";
								if(!$oProyectos->BusquedaAvanzada($datos,$resultadoproyectos,$numfilasproyectos))
									return false;
								$color="";
								
								if ($numfilasproyectos>0){
									$total=$numfilasproyectos;
                                    ?>
                                     <a href="plan_proyectos.php" title="Ir a proyectos">
                                                <div class="chart <?php  echo $color; ?>"  data-percent="100" data-barcolor="#3498DB" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                                        <span class="percent" ><?php  echo $numfilasproyectos;?></span>
                                                        <p>Proyectos</p>
                                                        <canvas height="150" width="150">
                                                 </div>
                                       </a>
                                       <?php 
                                    }
                                ?>
                                <?php  
								//proyectos abiertos
								$otags = new cPlanProyectosTags($conexion);
								$datos= array();
								if(!$otags->BusquedaAvanzada($datos,$resultadotags,$numfilastags))
									return false;
								$color="";
								
								if ($numfilastags>0){
                                    ?>
                                     <a href="plan_tags.php" title="Ir a tags">
                                                <div class="chart <?php  echo $color; ?>"  data-percent="100" data-barcolor="#3498DB" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                                        <span class="percent" ><?php  echo $numfilastags?></span>
                                                        <p>Tags</p>
                                                        <canvas height="150" width="150">
                                                 </div>
                                       </a>
                                       <?php 
                                    }
                                ?>
                                 <?php  
								//proyectos abiertos
								$oObjetivos = new cPlanObjetivos($conexion);
								$datos['planobjetivoestado'] = ACTIVO;
								if(!$oObjetivos->BusquedaAvanzada($datos,$resultadoOBJ,$numfilasOBJ))
									return false;
								$color="";
								
								if ($numfilasOBJ>0){
                                    ?>
                                     <a href="plan_objetivos.php" title="Ir a objetivos">
                                                <div class="chart <?php  echo $color; ?>"  data-percent="100" data-barcolor="#3498DB" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                                        <span class="percent" ><?php  echo $numfilasOBJ?></span>
                                                        <p>Objetivos</p>
                                                        <canvas height="150" width="150">
                                                 </div>
                                       </a>
                                       <?php 
                                    }
                                ?>
                                <?php  
								//proyectos abiertos
								$oEjes = new cPlanEjes($conexion);
								$datos['planejeestado'] = ACTIVO;
								if(!$oEjes->BusquedaAvanzada($datos,$resultadoproyectos,$numfilasEjes))
									return false;
								$color="";
								
								if ($numfilasEjes>0){
                                    ?>
                                     <a href="plan_ejes.php" title="Ir a Ejes">
                                                <div class="chart <?php  echo $color; ?>"  data-percent="100" data-barcolor="#3498DB" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                                        <span class="percent" ><?php  echo $numfilasEjes?></span>
                                                        <p>Ejes</p>
                                                        <canvas height="150" width="150">
                                                 </div>
                                       </a>
                                       <?php 
                                    }
                                ?>
                                </div> <!--  CIERRE wrp row no-margin -->
                            </div><!--  CIERRE wrapper-circle-charts -->
                    </div><!--  CIERRE white-panel-widget clearfix m-sidebar m-bot-30 no-pad -->
                </div><!--  CIERRE col-md-10 -->
                
                
                <div class="col-md-4" style="margin-top:40px" >
                    <div class="widget-wrapper">
                        <div class="panel-widget">                        
                       	 <span>Cuando acced&iacute;?</span>
                        <div class="content extra clearfix">
                            <?php  if ($tieneultacceso){?>
                                <ul class="wrapper-items">
                                    <li style="font-weight:bold">&Uacute;ltimo Acceso:&nbsp;&nbsp;</b><span style="font-style:italic"><?php  echo FuncionesPHPLocal::ConvertirFecha($datosultimoacceso['usuariofecha'],"aaaa-mm-dd","dd/mm/aaaa");?>&nbsp;<?php  echo substr($datosultimoacceso['usuariofecha'],11,5)."Hs.";?></span>
                                    <li style="font-weight:bold">Desde IP:</b> <?php  echo $datosultimoacceso['usuarioip'];?>
                                </ul>
                            <?php  }?>
                        </div>
                    </div>
                </div>
               </div>

                 <div class="col-md-4" style="margin-top:20px"  >
                        <div class="white-panel-widget clearfix m-sidebar m-bot-30 no-pad">
                            <div class="average-statistics-wrapper row no-margin">
                                <h3>Espacio en Disco</h3>
                            </div>
                            <div class="chart <?php  echo $color; ?>" style=" margin:8% 0 5% 25%"  data-percent="<? echo $datosEspacio["porcentaje"]?>" data-barcolor="<? echo $datosEspacio["msje"]; ?>" data-linecap="butt" data-linewidth="12" data-trackcolor="#C2E0F4" data-size="150">
                                    <span style="font-size:1.6em; color:<? echo $datosEspacio["msje"]; ?>" class="percent" ><?php  echo sprintf('%1.2f' ,$datosEspacio["porcentaje"]);?> %</span>
                                    <p>Espacio Utilizado</p>
                                    <canvas height="200" width="200">
<div class="clear"></div>
                             </div>
                         </div>
                     </div>
                   <div class="clear"></div>

            </div>               
            <div class="clear"></div>

            </div>



</div>
<div class="clear"></div>    

<script type="text/javascript" src="js/easycharts.js"></script>

<script type="text/javascript">

$(function(){
	init_easypiechart(<?php  echo "1"?>);
});




</script>
<?php 

 $oEncabezados->PieMenuEmergente();
?>
