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

$oObjeto = new cPlanEjes($conexion);

?>
<script type="text/javascript" src="modulos/plan_ejes/js/plan_ejes.js"></script>
	
<div class="form">
    <form action="plan_ejes.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="inner-page-title" style="padding-bottom:2px;">
            <h1><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp;Ejes</h1>
        </div>
        <div class="col-md-12 col-xs-12 col-sm-12">
                <div class="form-group clearfix">
                                                                    
                    <div class="col-md-4">
                        <label>C&oacute;digo:</label>
                        <input name="planejecod" id="planejecod" class="form-control input-md" type="text"  maxlength="11" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['planejecod'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['planejecod'],ENT_QUOTES) : '';?>" />
                    </div>
                                                                                                
                    <div class="col-md-4">
                        <label>Nombre:</label>
                        <input name="planejenombre" id="planejenombre" class="form-control input-md" type="text"  maxlength="255" size="60" value="<?php echo (isset($_SESSION['BusquedaAvanzada']['planejenombre'])) ? FuncionesPHPLocal::HtmlspecialcharsBigtree($_SESSION['BusquedaAvanzada']['planejenombre'],ENT_QUOTES) : '';?>" />
                    </div>
                                                                                                
                    
                </div>
                <div class="clearboth brisa_vertical">&nbsp;</div>
		</div>
        <input type="hidden" name="planejeestado" id="planejeestado" value="<?php echo ACTIVO.",".NOACTIVO ?>" /> 	
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
        	<a class="btn btn-success" href="plan_ejes_am.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Crear nuevo Eje</a>
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