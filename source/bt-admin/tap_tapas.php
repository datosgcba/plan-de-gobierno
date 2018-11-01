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

$oTapas= new cTapas($conexion);

$_SESSION['msgactualizacion'] = "";
$tapatipocod="";
?>

<script type="text/javascript" src="modulos/tap_tapas/js/tap_tapas.js?v=1.1"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h1><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Portadas</h1>
</div>
<div class="form">
    <form action="tap_tapas.php" method="post" name="formbusqueda" class="general_form" id="formbusqueda" onSubmit="return gridReload()">
        <div class="row">
    
                <div class="col-md-6">
                    <label for="tapanom">Nombre:</label>
                    <input name="tapanom" id="tapanom" class="form-control input-md" type="text"  maxlength="100" size="60" value="" />
                </div>
        
                <div class="col-md-6">
                    <label for="tapatipocod">Tipo de portada:</label>
                     <?php  
                        $oTapasTipos = new cTapasTipos($conexion);
                        $oTapasTipos->TapasTiposSP($spnombre,$sparam);
                        FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$sparam,"formulario","tapatipocod","tapatipocod","tapatipodesc",$tapatipocod,"Todos...",$regactual,$seleccionado,1,"","",false,false,"",false,"form-control input-md");
                    ?>                     
                </div>
        
        </div>
        <div class="clear" style="height:1px;">&nbsp;</div>
        
    </form>
</div>
<div class="clearboth aire_vertical">&nbsp;</div>

<div class="row">
    <div class="col-md-8">
        <a class="btn btn-default" href="javascript:void(0)" onclick="gridReload()" title="Buscar"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</a>
        <a class="btn btn-default" href="javascript:void(0)" onclick="Resetear()" title="Limpiar Datos"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Limpiar</a>                
    </div>
    <div class="col-md-4">
        <div class="text-right">
            <a class="btn btn-success" href="javascript:void(0)" onclick="AltaTapas()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nueva Portada</a>
        </div>
    </div>
</div>	   	

<div class="clearboth aire_vertical">&nbsp;</div>
<div id="LstTapas" style="width:100%;">
       <table id="listarTapas"></table>
    <div id="pager2"></div>
</div>
<div id="Popup"></div>
<div class="clearboth aire_vertical">&nbsp;</div>

<div id="ModalPortadas" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Portadas</h4>
      </div>
      <div class="modal-body">
        <div id="DataPortada">
            
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