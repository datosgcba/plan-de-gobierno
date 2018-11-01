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

$oMenu= new cTapasMenu($conexion);

$oMenuTipo = new cTapasMenuTipos($conexion);
if(!$oMenuTipo->BuscarxCodigo($_GET,$resultado,$numfilas))
	return false;
if ($numfilas!=1)
{
	FuncionesPHPLocal::MostrarMensaje($conexion,MSG_ERRGRAVE,"Error, tipo de menu inexistente. ",array("archivo" => __FILE__,"funcion" => __FUNCTION__, "linea" => __LINE__),array("formato"=>FMT_TEXTO));
	return false;
}	
	
$datostipo = $conexion->ObtenerSiguienteRegistro($resultado);


if(!$oMenu->BuscarxTipo($_GET,$resultado,$numfilas))
	return false;

$_SESSION['msgactualizacion'] = "";
$menutipocod = $_GET['menutipocod'];
$nivel = 1;
function CargarSubMenu($arbol,$nivel)
{
	$margen = $nivel *10; 
	?>
    <ol>
    <? 
	foreach($arbol as $fila)
	{
		?>
            <li id="menu_<? echo $fila['menucod']?>" class="clearfix">
               <div class="menuseleccionado clearfix">
                    <div style="float:left; width:70%">
                    <div class="menudesc"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menudesc'],ENT_QUOTES);?></div>
                    <div style="font-size:10px;">Acces Key: <? echo $fila['menuaccesskey']?></div>
                    </div>
                    <div style="float:left; width:30%">
                        <a href="javascript:void(0)" class="menuhandle" title="Mover Menu">
                            <img src="images/move.png" alt="Mover Menu" />
                        </a>
                        &nbsp;
                        <a href="javascript:void(0)" onclick="EditarMenu(<? echo $fila['menucod']?>)" title="Editar Menu">
                            <img src="images/edit_action.gif" alt="Editar Menu" />
                        </a>
                        &nbsp;
                        <a href="javascript:void(0)" onclick="EliminarMenu(<? echo $fila['menucod']?>)" title="Eliminar Menu">
                            <img src="images/cross.png" alt="Eliminar Menu" />
                        </a>
                    </div>
              </div>        
              <div class="clearboth" style="height:1px; font-size:1px;">&nbsp;</div>
					<? 
					if (isset($fila['subarbol']) && count($fila['subarbol'])>0)
                    {
                        $nivel ++;
                        CargarSubMenu($fila['subarbol'],$nivel);
                        $nivel --;
                    }?>
          </li>      
		<?	
	}
	?>
    	</ol>
    <? 
}

$oMenu-> ArmarArbol($datostipo,"",$arbol);

?>
<link rel="stylesheet" type="text/css" href="modulos/tap_tapas/css/tap_menu.css" />
<script type="text/javascript" src="modulos/tap_tapas/js/tap_menu.js?v=2.1"></script>
<script type="text/javascript" src="modulos/tap_tapas/js/tap_menu_dominios.js?v=2.1"></script>
<div class="inner-page-title" style="padding-bottom:2px;">
    <h2>Men&uacute; <? echo $datostipo['menutipodesc']?></h2>
</div>

<div class="clearboth">&nbsp;</div>
<div id="MenuCarga" style="width:500px;">
    <ol class="sortable">
    <? 
		foreach($arbol as $fila)
		{
            ?>
                <li id="menu_<? echo $fila['menucod']?>"  class="clearfix">
                   <div class="menuseleccionado clearfix">
                        <div style="float:left; width:70%">
                        <div class="menudesc"><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($fila['menudesc'],ENT_QUOTES);?></div>
                        <div style="font-size:10px;">Acces Key: <? echo $fila['menuaccesskey']?></div>
                        </div>
                        <div style="float:left; width:30%">
                            <a href="javascript:void(0)" class="menuhandle" title="Mover Menu">
                                <i class="fa fa-arrows" aria-hidden="true"></i>&nbsp;Mover
                            </a>
                            &nbsp;
                            <a href="javascript:void(0)" onclick="EditarMenu(<? echo $fila['menucod']?>)" title="Editar Menu">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>&nbsp;Editar
                            </a>
                            &nbsp;
                            <a href="javascript:void(0)" onclick="EliminarMenu(<? echo $fila['menucod']?>)" title="Eliminar Menu">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>&nbsp;Eliminar
                            </a>
                        </div>
                 	</div>
					<? 
                    if (isset($fila['subarbol']))
                    {
                        $nivel ++;
                        CargarSubMenu($fila['subarbol'],$nivel);
                        $nivel --;
                    }?>
                            
                </li>    
            <? 
        }
    
    ?>
    </ol>
</div>    
<div class="clearboth">&nbsp;</div>
<div class="form">
	<form action="tap_menu_upd.php" method="post" name="menusuperior">
        <div class="clearboth aire_menor">&nbsp;</div>      
        <div class="menubarra">
            <ul>
                <li><a class="btn btn-success" name="AgregarMenu" title="Agregar Menu" href="javascript:void(0)"  onclick="AgregarMenu()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar Men&uacute;</a></li>
                <li><a class="btn btn-info" name="Publicar" title="Publicar" href="javascript:void(0)"  onclick="publicarMenu()"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;Publicar Men&uacute;</a></li>
                <li><a class="btn btn-primary" href="tap_menu_tipos.php" title="Volver"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Volver</a></li>
            </ul>
        </div>
        <div class="clearboth aire_menor">&nbsp;</div>      
        <input type="hidden" value="<? echo $menutipocod?>" name="menutipocod" id="menutipocod" />
    </form>
</div>
<div class="clearboth">&nbsp;</div>
<div id="ModalAlta" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 class="modal-title"><i class="fa fa-table" aria-hidden="true"></i>&nbsp;Menu</h4>
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

<?
$oEncabezados->PieMenuEmergente();
$_SESSION['msgactualizacion']="";
?>