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
?>

<link rel="stylesheet" type="text/css" href="modulos/pag_paginas/css/categorias.css" />
<script type="text/javascript" src="modulos/pag_paginas/js/categorias.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <div class="row">
        <div class="col-md-10">
            <h1><i class="fa fa-th-list"></i>&nbsp;Categorias</h1>
        </div>
    </div>
</div>
 
<div class="txt_izq">
     <form action="gal_albums.php" method="post" name="formbusqueda" id="formbusqueda">
		<input type="hidden" name="catsuperior" id="catsuperior" value="" />
        <input type="hidden" name="catestado" id="catestado" value="<?php  echo ACTIVO.",".NOACTIVO ?>" />
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>
<div class="menubarra">
    <a class="btn btn-success" href="pag_categorias_am.php"><i class="fa fa-plus"></i>&nbsp;Crear nueva categoria</a>
</div>
<?php  
	//$oPaginasCategorias->MostrarJerarquia($catcod,$jerarquia,$nivel);
	//print_r ($jerarquia);
?>
<div id="Popup"></div>
<div class="clear aire_vertical">&nbsp;</div>
<div id="LstPaginasCategorias" style="width:100%;">
    <table id="ListarPaginasCategorias"></table>
    <div id="pager2"></div>
</div>

<?php 
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>