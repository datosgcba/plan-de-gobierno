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

// ve si el sistema est� bloqueado
$oSistemaBloqueo = new SistemaBloqueo();
$oSistemaBloqueo->VerificarBloqueo($conexion);

$oEncabezados = new cEncabezados($conexion);
$oEncabezados->EncabezadoMenuEmergente($_SESSION['rolcod'],$_SESSION['usuariocod']);

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
$volver= "not_noticias_workflow.php"; 

//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$Workflow = new cPaginasWorkflowRoles($conexion,"");
?>

<script type="text/javascript" src="modulos/pag_paginas/js/pag_paginas_workflow_roles.js"></script>
<script type="text/javascript">
</script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Workflow de P&aacute;ginas</h2>
</div>
    
<div class="form">
<form action="pag_paginas_workflow_roles.php" method="post" name="formbusqueda"  class="general_form" id="formbusqueda" >
	
            <div class="ancho_1">
                <label>Rol:</label>
            </div>
            <div class="ancho_2">
            	<?php 	
					$roles=new cRoles($conexion);
					$roles->RolesSP($spnombre,$spparam);
					FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formbusqueda","rolcodbusqueda","rolcod","roltodo","","Todos...",$regnousar,$selecnousar,1,"doSearch(arguments[0]||event)","","",false);
            	?>
            </div>
            <div class="ancho_05">&nbsp;</div>
            <div class="ancho_1">
                <label>Estado:</label>
            </div>
            <div class="ancho_2">
            	<?php 
					$oPaginasEstados=new cPaginasEstados($conexion);
					$oPaginasEstados->PaginasEstadosSP($spnombre,$spparam);
					FuncionesPHPLocal::ArmarCombo_SinSalto_BD($conexion,$spnombre,$spparam,"formbusqueda","pagestadocodbusqueda","pagestadocod","pagestadodesc","","Todos...",$regnousar,$selecnousar,1,"doSearch(arguments[0]||event)","","",false);
				?>
            </div>
       <div class="clear fixalto">&nbsp;</div>
</form>
</div>
<div class="clear aire_vertical">&nbsp;</div>

<div class="menubarra">
    <ul>
        <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="AltaWorkflow()">Agregar workflow</a></div></li>
        <li><div class="ancho_boton aire"><a class="boton base" href="javascript:void(0)" onclick="Resetear()">Limpiar Busqueda</a></div></li>
    </ul>    
</div>

         		
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstWorkflow" style="width:100%;">
    <table id="ListarWorkflow"></table>
    <div id="pager2"></div>
</div>
    

<div class="clearboth">&nbsp;</div>
    


<?php  
$oEncabezados->PieMenuEmergente();
?>