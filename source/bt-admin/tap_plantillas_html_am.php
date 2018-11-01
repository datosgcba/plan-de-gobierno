<? 
require('./config/include.php');
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

$oPlantillas=new cPlantillasHtml($conexion);

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla

$mensajeaccion = "";
if (isset($_SESSION['msgactualizacion']) && $_SESSION['msgactualizacion']!="")
	$mensajeaccion = '<p class="msg done">'.$_SESSION['msgactualizacion'].'</p>';

$planthtmlcod="";
$planthtmldesc="";
$planthtmldisco="";
$planthtmlheader = "";
$planthtmlfooter = "";
$planthtmldefault = 0;
$accion = 1;
$edit = false;
$funcionJs="return Insertar()";
$boton = "botonalta";
$botontexto = "Alta de Plantilla HTML";
$esbaja  = false;

if (isset($_GET['planthtmlcod']) && $_GET['planthtmlcod']!="")
{
	FuncionesPHPLocal::ArmarLinkMD5(basename($_SERVER['PHP_SELF']),array("planthtmlcod"=>$_GET['planthtmlcod']),$get,$md5);
	if($_GET["md5"]!=$md5)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Acción Ilegal.",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		$oEncabezados->PieMenuEmergente();
		die();
	}
	
	$planthtmlcod = $_GET['planthtmlcod'];
	if (!$oPlantillas->BuscarxCodigo($_GET,$resultado,$numfilas))
		return false;
	
	if ($numfilas!=1)
	{
		FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error al buscar la plantilla html por codigo. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
		die();
	}
	$datosencontrados = $conexion->ObtenerSiguienteRegistro($resultado);	


	$funcionJs="return Actualizar()";
	$edit = true;
	$boton = "botonmodif";
	$accion = 2;
	$botontexto = "Actualizar Plantilla HTML";

	$planthtmlcod = $datosencontrados['planthtmlcod']; 
	$planthtmldesc = $datosencontrados['planthtmldesc']; 
	$planthtmldisco = $datosencontrados['planthtmldisco']; 
	$planthtmlheader = $datosencontrados['planthtmlheader']; 
	$planthtmlfooter = $datosencontrados['planthtmlfooter']; 
	$planthtmldefault = $datosencontrados['planthtmldefault']; 
}

FuncionesPHPLocal::ArmarLinkMD5("tap_plantillas_html_upd.php",array("planthtmlcod"=>$planthtmlcod),$getupd,$md5upd);

?>
<link href="modulos/tap_plantillas/css/plantillas_html_am.css" rel="stylesheet" title="style" media="all" />
<script type="text/javascript" src="modulos/tap_plantillas/js/tap_plantillas_html_am.js"></script>

<div id="contentedor_modulo">
	<div id="contenedor_interno">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h1><i class="fa fa-code" aria-hidden="true"></i>&nbsp;Plantillas HTML</h1>
        </div>  
        <div class="form">
            <form action="tap_plantillas_html_upd.php" method="post" name="formulario" id="formulario">
                <input type="hidden" name="planthtmlcod" id="planthtmlcod" value="<? echo $planthtmlcod;?>" />
                <input type="hidden" name="md5" id="md5" value="<? echo $md5upd;?>" />
                <input type="hidden" name="accion" id="accion" value="<? echo $accion?>">
                <div class="row">
                     <div class="col-md-12">
                        <div class="datosgenerales">
                            <div class="form-group">
                            	<div class="row">
                                    <div class="col-md-6">
                                        <label>Descripci&oacute;n:</label>
                                        <input type="text" name="planthtmldesc" id="planthtmldesc" class="form-control input-md" maxlength="80" size="60" value="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmldesc,ENT_QUOTES);?>" />
                                    </div>
                                    <div class="col-md-6">
                                        <label>Ubicaci&oacute;n plantilla:</label>
                                        <input type="text" name="planthtmldisco" id="planthtmldisco" class="form-control input-md" maxlength="255" size="60" value="<? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmldisco,ENT_QUOTES);?>" />
                                    </div>
                                    <div class="clearboth"></div>
                            	</div>
                            </div>
                            <div class="form-group">
                                <label>Es plantilla Default:</label>
                                <div class="clerboth"></div>
                                  <label class="radio-inline"><input type="radio" name="planthtmldefault" <? if ($planthtmldefault==0) echo 'checked="checked"'?>  id="planthtmldefault_no" value="0" />No</label>
                                  <label class="radio-inline"><input type="radio" name="planthtmldefault" <? if ($planthtmldefault==1) echo 'checked="checked"'?> id="planthtmldefault_si" value="1" />Si</label>
                               	<div class="clearboth aire_menor">&nbsp;</div>
                            </div>   

                            <div class="form-group">
                                <label>HTML Header</label>
                                <textarea name="planthtmlheader" id="planthtmlheader" class="form-control input-md" rows="15" cols="40" ><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmlheader,ENT_QUOTES);?></textarea>
                            </div>
            
                            <div class="form-group">
                                <label>HTML Footer</label>
                                <textarea name="planthtmlfooter" id="planthtmlfooter" class="form-control input-md" rows="15" cols="40" ><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($planthtmlfooter,ENT_QUOTES);?></textarea>
                            </div>
                            <div class="clearboth aire_menor">&nbsp;</div>
            
                       </div>
                    </div>
            
                    <div style="clear:both">&nbsp;</div>
                </div>
                <div class="clear aire_vertical">&nbsp;</div>
                <div class="menuAcciones accionespagina">
                    <a class="btn btn-success" href="javascript:void(0)" onclick="<? echo $funcionJs?>"><i class="fa fa-save" aria-hidden="true"></i>&nbsp;<? echo $botontexto?></a>
                    <? if ($edit) {?>
                        <a class="btn btn-danger" href="javascript:void(0)" onclick="Eliminar(<? echo $planthtmlcod;?>)"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Eliminar</a>
                    <? }?>
                    <a class="btn btn-default" href="tap_plantillas_html.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Volver</a>
                 </div>
            </form>
        </div>
	</div>
</div>    
					<div id="MsgGuardar" class="snackbar success"></div>

<div class="clear aire_vertical">&nbsp;</div>
<?
$_SESSION['msgactualizacion']="";
$oEncabezados->PieMenuEmergente();
?>