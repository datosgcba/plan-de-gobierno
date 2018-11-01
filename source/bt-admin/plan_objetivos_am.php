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

$oObjeto = new cPlanObjetivos($conexion);
$oPlanSellos = new cPlanSellos($conexion);

$esmodif = false;
$botonejecuta = "BtAlta";
$boton = "Alta";
$onclick = "return Insertar();";
$planobjetivocod = "";
$planobjetivonombre = "";
$planobjetivodescripcion = "";
$planobjetivoestado = "";
$planobjetivosellos = "";
if (isset($_GET['planobjetivocod']) && $_GET['planobjetivocod']!="")
{
	$esmodif = true;
	$datos = $_GET;
	if(!$oObjeto->BuscarxCodigo($datos,$resultado,$numfilas))
		return false;
	if($numfilas!=1){
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Codigo inexistente.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		return false;
	}
	$datosregistro = $conexion->ObtenerSiguienteRegistro($resultado);
	$onclick = "return Modificar();";
	$planobjetivocod = $datosregistro["planobjetivocod"];
	$planobjetivonombre = $datosregistro["planobjetivonombre"];
	$planobjetivodescripcion = $datosregistro["planobjetivodescripcion"];
	$planobjetivoestado = $datosregistro["planobjetivoestado"];
	$sellos = explode(",",$datosregistro["planobjetivosellos"]);
	foreach($sellos as $sello)
	{
		$planobjetivosellos[$sello] = $sello;
	}
}

	$datosSellos['selloestado'] = 10;
	if(!$oPlanSellos->BusquedaAvanzada($datosSellos,$resultadoSellos,$numfilasSellos))
		return false;
	

?>
<script type="text/javascript" src="js/tiny_mce/tiny_mce.min.js"></script>
<script type="text/javascript" src="modulos/plan_objetivos/js/plan_objetivos_am.js"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
     <h1><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Objetivo</h1>
</div>
<div class="clear fixalto">&nbsp;</div>
<div style="text-align:left;">
	<div class="form">
   		
	<form action="plan_objetivos.php" method="post" name="formalta" id="formalta" >
        <div class="col-md-7 col-xs-12 col-sm-6">				
			<div class="form-group clearfix"><label for="planobjetivonombre">Nombe</label>
			<input type="text" class="form-control input-md" maxlength="255" name="planobjetivonombre" id="planobjetivonombre" value="<?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planobjetivonombre,ENT_QUOTES)?>" />
			
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
                            			<div class="form-group clearfix"><label for="planobjetivodescripcion">Descripci&oacute;n</label>
                                    <textarea class="form-control input-md rich-text" rows="6" cols="20" name="planobjetivodescripcion" id="planobjetivodescripcion"><?php   echo $planobjetivodescripcion?></textarea>
			
			

                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>
		                    <input type="hidden" name="planobjetivocod" id="planobjetivocod" value="<?php   echo $planobjetivocod?>" />                        
                	<div class="menubarraInferior">
                    <div class="menubarra">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="btn btn-success" href="javascript:void(0)" onclick="<?php   echo $onclick ?>">Guardar</a></div></li>
                            <li><div class="ancho_boton aire"><a class="btn btn-default" href="plan_objetivos.php"><i class="fa fa-backward"></i>&nbsp;Volver</a></div></li>
                        	                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>
					<div id="MsgGuardar" class="snackbar success"></div>
                    <div class="menubarra pull-right">
                        <ul>
                            <li><div class="ancho_boton aire"><a class="btn btn-info" href="plan_objetivos_am.php">Crear nuevo </a></div></li>
                        </ul>
                        <div class="clearboth">&nbsp;</div>
                    </div>    
                    
                </div>
                <div class="clearboth">&nbsp;</div>
        </div>
        
        <div class="col-md-5 col-xs-12 col-sm-6">
        					      	<div class="form-group clearfix">
                                <label for="planobjetivosellos[]">Sellos (Marque haciendo ctrl+click)</label>
                                <select style="height:300px !important;" class="form-control input-md" name="planobjetivosellos[]" id="planobjetivosellos[]" multiple>
                                    <? while($filasellos = $conexion->ObtenerSiguienteRegistro($resultadoSellos)){?>
                                        <option value="<? echo $filasellos['sellocod']; ?>" <? echo (array_key_exists($filasellos['sellocod'],$planobjetivosellos))?"selected='selected'":"";?>><?php   echo FuncionesPHPLocal::HtmlspecialcharsBigtree($filasellos['sellocod']." - ".$filasellos['sellonombre'],ENT_QUOTES)?></option>
                                    <? } ?>
                                </select>
                                <div class="clearboth brisa_vertical">&nbsp;</div>
                            </div>
                            <div class="clearboth brisa_vertical">&nbsp;</div>

		                <div class="txt">Recuerde <strong>guardar</strong> para que se realicen los cambios</div>
                                
                
            </div>
       </form>
        <div class="clearboth">&nbsp;</div>
    </div>
    <div class="clearboth">&nbsp;</div>
</div>


<?php 
$oEncabezados->PieMenuEmergente();

?>