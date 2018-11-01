<?php 
require("./config/include.php");

$conexion = new accesoBDLocal(SERVIDORBD,USUARIOBD,CLAVEBD);
$conexion->SeleccionBD(BASEDATOS);

FuncionesPHPLocal::CargarConstantes($conexion,array("roles"=>"si","sistema"=>SISTEMA,"multimedia"=>"si"));
$conexion->SetearAdmiGeneral(ADMISITE);

$sesion = new Sesion($conexion,false);
$sesion->TienePermisos($conexion,$_SESSION['usuariocod'],$_SESSION['rolcod'],$_SERVER['PHP_SELF']);

$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$oObjeto = new cPlanProyectos($conexion);

if(!$oObjeto->plan_objetivosSPResult($result_plan_objetivos,$numfilas_plan_objetivos))
	return false;
if(!$oObjeto->plan_jurisdiccionesSPResult($result_plan_jurisdicciones,$numfilas_plan_jurisdicciones))
	return false;
if(!$oObjeto->plan_proyectos_estadosSPResult($result_plan_proyectos_estados,$numfilas_plan_proyectos_estados))
	return false;
?>
<script type="text/javascript" src="modulos/plan_proyectos/js/plan_proyectos.js?v=1.0"></script>
	
<div class="form">
    <form action="plan_proyectos.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h1><i class="fa fa-archive" aria-hidden="true"></i>&nbsp;Proyectos</h1>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            							<div class="form-group clearfix">
													                            
								<div class="col-md-4">
									<label>C&oacute;digo:</label>
									<input name="planproyectocodigo" id="planproyectocodigo" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['planproyectocodigo'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['planproyectocodigo'],ENT_QUOTES) : '';?>" />
								</div>
																				                            
								<div class="col-md-4">
									<label>Nombre:</label>
									<input name="planproyectonombre" id="planproyectonombre" class="form-control input-md" type="text"  maxlength="255" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['planproyectonombre'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['planproyectonombre'],ENT_QUOTES) : '';?>" />
								</div>
																				                            <div class="col-md-4">
                                    <label>Objetivo:</label>
									<select class="form-control input-md" name="planobjetivocod" id="planobjetivocod">
                        				<option value="">Todos...</option>
                                        
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_objetivos)){?>
								<option <?php if (isset($_SESSION['BusquedaAvanzada']['planobjetivocod']) && $filaCombo['planobjetivocod']==$_SESSION['BusquedaAvanzada']['planobjetivocod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['planobjetivocod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planobjetivonombre'],ENT_QUOTES);?></option>
							<?php }?>
									</select>

								</div>
                            						                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
														<div class="form-group clearfix">
													                            <div class="col-md-4">
                                    <label>Jurisdicci&oacute;n:</label>
									<select class="form-control input-md" name="planjurisdiccioncod" id="planjurisdiccioncod">
                        				<option value="">Todos...</option>
                                        
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_jurisdicciones)){?>
								<option <?php if (isset($_SESSION['BusquedaAvanzada']['planjurisdiccioncod']) && $filaCombo['planjurisdiccioncod']==$_SESSION['BusquedaAvanzada']['planjurisdiccioncod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['planjurisdiccioncod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planjurisdiccionnombre'],ENT_QUOTES);?></option>
							<?php }?>
									</select>

								</div>
                            													                            <div class="col-md-4">
                                    <label>Estado:</label>
									<select class="form-control input-md" name="planproyectoestadocod" id="planproyectoestadocod">
                        				<option value="">Todos...</option>
                                        
							<?php while($filaCombo = $conexion->ObtenerSiguienteRegistro($result_plan_proyectos_estados)){?>
								<option <?php if (isset($_SESSION['BusquedaAvanzada']['planproyectoestadocod']) && $filaCombo['planproyectoestadocod']==$_SESSION['BusquedaAvanzada']['planproyectoestadocod']) echo 'selected="selected"'?> value="<?php echo $filaCombo['planproyectoestadocod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filaCombo['planproyectoestadonombre'],ENT_QUOTES);?></option>
							<?php }?>
									</select>

								</div>
                            						        </div>
        			<input type="hidden" name="planproyectoestado" id="planproyectoestado" value="<?php echo ACTIVO.",".NOACTIVO ?>" /> 	
					</div>
		    </form>   
</div> 
<div class="clear aire_vertical">&nbsp;</div>

<div class="row">
     <div class="col-md-6">
        <a class="btn btn-info" href="javascript:void(0)" onclick="gridReload()"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</a>
    	<a class="btn btn-default" href="javascript:void(0)" onclick="Resetear()"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Limpiar</a>
    </div>
      <div class="col-md-6">
       <div class="pull-right">
        	<a class="btn btn-success" href="plan_proyectos_am.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Crear nuevo Proyecto</a>
		</div>
       </div>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="clear" style="height:1px;">&nbsp;</div>
<div id="LstDatos" style="width:100%;">
       <table id="listarDatos"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
	
<?php 
$oEncabezados->PieMenuEmergente();

?>