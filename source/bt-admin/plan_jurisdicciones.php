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

$oObjeto = new cPlanJurisdicciones($conexion);

?>
<script type="text/javascript" src="modulos/plan_jurisdicciones/js/plan_jurisdicciones.js"></script>
	
<div class="form">
    <form action="plan_jurisdicciones.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
             <h1> <i class="fa fa-sitemap" aria-hidden="true"></i></i>&nbsp;Jurisdicciones</h1>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
            <div class="form-group clearfix">
                <div class="col-md-4">
                    <label>C&oacute;digo:</label>
                    <input name="planjurisdiccioncod" id="planjurisdiccioncod" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['planjurisdiccioncod'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['planjurisdiccioncod'],ENT_QUOTES) : '';?>" />
                </div>
                                                                                            
                <div class="col-md-4">
                    <label>Nombre:</label>
                    <input name="planjurisdiccionnombre" id="planjurisdiccionnombre" class="form-control input-md" type="text"  maxlength="255" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['planjurisdiccionnombre'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['planjurisdiccionnombre'],ENT_QUOTES) : '';?>" />
                </div>
            </div>
	        <input type="hidden" name="planjurisdiccionestado" id="planjurisdiccionestado" value="<?php echo ACTIVO.",".NOACTIVO ?>" /> 	
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
        	<a class="btn btn-success" href="plan_jurisdicciones_am.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Crear nueva Jurisdicci&oacute;n</a>
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