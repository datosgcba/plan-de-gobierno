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

$_SESSION['datosusuario'] = $_SESSION['busqueda'] = array();
//----------------------------------------------------------------------------------------- 	
// Inicio de pantalla
$oPaginas = new cPaginas($conexion,"");

$pagcod = "";
$pagtitulo = "";
$catcod = "";
$pagestadocod = "";
if (isset($_SESSION['datosbusquedafiltropagina']) && count($_SESSION['datosbusquedafiltropagina'])>0)
{
	if (isset($_SESSION['datosbusquedafiltropagina']['pagestadocod']))
       $pagestadocod = $_SESSION['datosbusquedafiltropagina']['pagestadocod'];
    if (isset($_SESSION['datosbusquedafiltropagina']['pagcod']))
       $pagcod = $_SESSION['datosbusquedafiltropagina']['pagcod'];
    if (isset($_SESSION['datosbusquedafiltropagina']['pagtitulo']))
       $pagtitulo = $_SESSION['datosbusquedafiltropagina']['pagtitulo'];
    if (isset($_SESSION['datosbusquedafiltropagina']['catcod']))
       $catcod = $_SESSION['datosbusquedafiltropagina']['catcod'];
}
	
$oPaginasEstados = new cPaginasEstados($conexion);
$datos = array();
if(!$oPaginasEstados->ObtenerEstadosCantidades($datos,$resultadopermisos,$numfilaspermisos))
	return false;
	
	
function CargarCategorias($catnom,$arreglocategorias,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		 $catnom2 = $fila['catnom'];
		 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
			$catnom2 .="  (".$fila['estadonombre'].")";
		
		?>

        <option <? if (array_key_exists($fila['catcod'],$arreglocategorias)) echo 'selected="selected"'?>  value="<? echo $fila['catcod']?>"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom),ENT_QUOTES).$nivel.FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>
		<? 
		if (isset($fila['subarbol']) && count ($fila['subarbol'])>0)
		{
			$catnom = $catnom.html_entity_decode(" &raquo;&raquo; ").$catnom;
			CargarCategorias($catnom,$arreglocategorias,$fila['subarbol'],$nivel);
			//$nivel = substr($nivel,0,strlen($nivel)-strlen("&raquo;&raquo;"));
		}
	}
}


$pagcodsuperior="";	
?>
<link rel="stylesheet" type="text/css" href="modulos/pag_paginas/css/paginas.css" />
<script type="text/javascript" src="modulos/pag_paginas/js/paginas.js"></script>

<div class="inner-page-title" style="padding-bottom:2px;">
    <div class="row">
        <div class="col-md-10">
            <h1><i class="fa fa-file-text-o"></i>&nbsp;Listado de P&aacute;ginas</h1>
        </div>
    <div class="clearboth"></div>
    </div>
</div>
<div class="form">
    <form action="pag_paginas.php" method="post" name="formbusqueda" id="formbusqueda">
		
        <div class="row">
            <div class="form-group">
                <div class="col-md-2">
                    <label>Id de P&aacute;gina:</label>
                    <input type="text" name="pagcod" id="pagcod" class="form-control input-md" maxlength="10"  value="<? echo $pagcod?>" />
                </div>
                <div class="col-md-6">
                    <label>Titulo:</label>
                    <input type="text" name="pagtitulo" id="pagtitulo" class="form-control input-md" maxlength="100" value="<? echo $pagtitulo?>" />
                </div>
                <div class="col-md-4">
                        <label>Categor&iacute;a:</label>
                        <?php
                            $oCategorias=new cPaginasCategorias($conexion);
                            $catsuperior="";
                            $estadocombocat = "";
                            if (!$oCategorias->ArmarArbolCategorias($catsuperior,$arbol,$estadocombocat))
                                $mostrar=false;
                            $arreglocategoriasSeleccionado = array();
                            
                        ?>
                        <select name="catcod" id="catcod" class="form-control input-md" >
                            <option value="">Todas</option>
                        <?php 
                            foreach($arbol as $fila)
                            {
    
                                 $catnom2 =  $catnom =$fila['catnom'];
                                 if(isset($fila['catestado']) && $fila['catestado'] != ACTIVO)
                                    $catnom2 .="  (".$fila['estadonombre'].")";	
                                ?>
    
                                <option <?php if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php echo $fila['catcod']?>"><?php echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($catnom2),ENT_QUOTES)?></option>
    
                                <?php 
                                if (isset($fila['subarbol']))
                                {
                                    $nivel = " &raquo;&raquo; ";
                                    CargarCategorias($catnom,$arreglocategoriasSeleccionado,$fila['subarbol'],$nivel);
                                }
                            } ?>
                         </select>
                </div>
            </div>    
        </div>
        <div class="clear fixalto">&nbsp;</div>
        <input type="hidden" name="pagestadocod" value="<?= $pagestadocod?>" id="pagestadocod" />
        <input type="hidden" name="pagcodsuperior" id="pagcodsuperior" value="<? echo $pagcodsuperior?>" />
	</form>
</div>

<div class="clear aire_vertical">&nbsp;</div>


<div class="row">
    <div class="col-md-3">
        <a class="btn btn-default" href="javascript:void(0)" onclick="gridReload()" title="Buscar"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Buscar</a>
        <a class="btn btn-default" href="javascript:void(0)" onclick="Resetear()" title="Limpiar Datos"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Limpiar</a>                
    </div>
    <div class="col-md-7">
        <div class="btn-group">
            <a class="states btn <? echo ($pagestadocod=="")?"btn-info":"btn-default";?>" href="javascript:void(0)"  onclick="FilterStates(this,'');">Todos</a>
            <? 
            $i=1;
            while ($fila = $conexion->ObtenerSiguienteRegistro($resultadopermisos)) {
                $class="middle";
                if ($i==$numfilaspermisos)
                    $class="right";
                
                $selected = "btn-default";
                if ($fila['pagestadocod']==$pagestadocod)
                    $selected = " btn-info";
                
                $cantidad="";
                if ($fila['pagestadomuestracantidad'])
                    $cantidad = " <span class='negro'>(".$fila['total'].")</span>";
                
                ?>
                <a class="btn <? echo $selected?> states" href="javascript:void(0)" onclick="FilterStates(this,<?= $fila['pagestadocod']?>)"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['pagestadodesc'],ENT_QUOTES).$cantidad?></a>
            <? 
                $i++;
                
            }?>
        </div>    
    </div>
    <div class="col-md-2">
        <div class="text-right">
            <a class="btn btn-success" href="pag_paginas_am.php"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Nueva P&aacute;gina</a>
        </div>
    </div>
</div>	   	



<div class="clear aire_vertical">&nbsp;</div>

<div id="LstPaginas" style="width:100%;">
    <table id="ListarPaginas"></table>
    <div id="pager2"></div>
</div>
<div class="clearboth">&nbsp;</div>
    
<? 
$oEncabezados->PieMenuEmergente();
?>