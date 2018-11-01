<?php  
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


$_SESSION['msgactualizacion'] = "";


?>
<script type="text/javascript" src="modulos/tap_modulos_confeccionar/js/tap_modulos.js?v=1.2"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h1><i class="fa fa-table" aria-hidden="true"></i>&nbsp;M&oacute;dulos</h1>
</div>

<div class="form">
    <form action="tap_tapas_am.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda">
        <div class="row">
            <div class="col-md-6">
               <div class="form-group">
                    <label>Descripci&oacute;n:</label>
                    <input name="modulodesc" id="modulodesc" class="form-control input-md" type="text" maxlength="100" size="60" value="" />
               </div>
            </div>        
            <div class="clear aire_vertical">&nbsp;</div>
        </div>
        <div class="clear" style="height:1px;">&nbsp;</div>
    </form>
</div>
    
<div class="clear aire_vertical">&nbsp;</div>

<div class="row">
    <div class="col-md-8">
        <a class="btn btn-default" href="javascript:void(0)" onclick="gridReload()" title="Buscar"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</a>
        <a class="btn btn-default" href="javascript:void(0)" onclick="Resetear()" title="Limpiar Datos"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Limpiar</a>                
    </div>
    <div class="col-md-4">
        <div class="text-right">
            <a class="btn btn-info" href="tap_modulos_files.php"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;Archivos del m&oacute;dulos</a>
            <a class="btn btn-success" href="javascript:void(0)" onclick="AltaModulo()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nuevo m&oacute;dulo</a>
        </div>
    </div>
    <div class="clear aire_vertical">&nbsp;</div>
</div>	   	


<div class="clearboth aire_vertical">&nbsp;</div>

<div class="clearboth" style="height:1px;">&nbsp;</div>
<div id="LstModulos" style="width:100%;">
       <table id="listarModulos"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth aire_vertical">&nbsp;</div>

<div id="ModalAlta" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title"><i class="fa fa-table" aria-hidden="true"></i>&nbsp;M&oacute;dulo</h4>
      </div>
      <div class="modal-body">
        <div id="DataAlta">
            
        </div>
        <div class="clearboth"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="Guardar" onclick="Validar()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<div class="clearboth aire_vertical">&nbsp;</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>