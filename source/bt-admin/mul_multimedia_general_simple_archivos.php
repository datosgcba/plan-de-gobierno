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

if (!isset($_POST['campoDevolucion']) || $_POST['campoDevolucion']=="")	
	die();
$campoDevolucion = $_POST['campoDevolucion'];

function CargarCategorias($catcod,$arbol,$nivel)
{
	foreach($arbol as $fila)
	{
		?>
		<option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"';?>  value="<?php  echo $fila['catcod']?>"><?php  echo $nivel.FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
		<?php   
		if (isset($fila['subarbol']))
		{
			$nivel .= "---";
			CargarCategorias($catcod,$fila['subarbol'],$nivel);
			$nivel = substr($nivel,0,strlen($nivel)-3);
		}
	}
}

?>
<link href="modulos/mul_multimedia/css/popup.css?v=1.1" rel="stylesheet" title="style" media="all" />
<script type="text/javascript">var sizeLimitFile = <?php  echo TAMANIOARCHIVOS;?>;</script>
<script type="text/javascript" src="js/multimediaSelectorPopupArchivos.js"></script>
    <div id="mul_multimedia_archivos_<?php  echo $campoDevolucion?>">
        <ul>
            <li><a href="#mul_multimedia_subir_simple">Subir Archivo Nuevo</a></li>
            <li><a href="#mul_multimedia_repositorio_simple">Seleccionar un Archivo Existente</a></li>
        </ul>
        <div id="mul_multimedia_subir_simple">
        	
            <div class="form">
                <form action="javascript:void(0)" method="post"name="form_mul_multimedia_archivos" id="form_mul_multimedia_archivos">

                    <div class="ancho_10">
                        <div id="mul_multimedia_bt_subir_archivos"></div> 
                        <input type="hidden" name="mul_multimedia_size" id="mul_multimedia_size"  value="" />
                        <input type="hidden" name="mul_multimedia_name" id="mul_multimedia_name"  value="" />
                        <input type="hidden" name="mul_multimedia_file" id="mul_multimedia_file"  value="" />
                        <div class="clear fixalto">&nbsp;</div>
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                    <div class="ancho_3">
                    	<div id="mul_multimedia_previsualizar"></div>
                    </div>
                    <div class="ancho_7" id="mul_multimedia_descripcion" style="display:none">
						<?php 
                            $oMultimediaCategorias=new cMultimediaCategorias($conexion);
                            $catsuperior="";
                            if (!$oMultimediaCategorias->ArmarArbolCategorias($catsuperior,$arbol))
                                $mostrar=false;
                            
                            $catcod = "";
                        ?>               
                       <div class="ancho_3" style="padding-top:5px;">
                            <label>Categoria: </label>
                       </div>
                       <div class="ancho_1">&nbsp;</div>
                       <div class="ancho_6">
                        <select name="catcod" id="catcod" style=" width:100%;">
                            <option value="">Seleccione una Categor&iacute;a...</option>
                        <?php  
                            foreach($arbol as $fila)
                            {
                                ?>
                                <option <?php  if ($fila['catcod']==$catcod) echo 'selected="selected"'?>  value="<?php  echo $fila['catcod']?>"><?php  echo FuncionesPHPLocal::HtmlspecialcharsBigtree(strtolower($fila['catnom']),ENT_QUOTES)?></option>
                                <?php  
                                if (isset($fila['subarbol']))
                                {
                                    $nivel = "---";
                                    CargarCategorias($catcod,$fila['subarbol'],$nivel);
                                }
                            }
                            ?>
                         </select>                   			
                        </div>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                    	
                        <input type="text" name="multimedia_titulo" style="width:100%" id="multimedia_titulo" maxlength="255" class="full"></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                    	<textarea name="multimedia_desc" style="width:100%" id="multimedia_desc" cols="20" rows="4"></textarea>
	                    <div class="clear brisa_vertical">&nbsp;</div>
                        <div class="menubarra">
                            <ul>
                                <li><div class="ancho_boton aire"><a class="boton verde" href="javascript:void(0)" onclick="SubirArchivo()">Guardar Archivo</a></div></li>
                                <li><div class="ancho_boton aire"><a class="botin base" href="javascript:void(0)"  onclick="DialogClose()">Cerrar Ventana</a></div></li>
                            </ul>
                        </div>        
                    </div>
                    <div class="clear fixalto">&nbsp;</div>
                </form>        
            </div>
        </div>
        <div id="mul_multimedia_repositorio_simple">
        	<div id="TableMultimediawidth_<?php  echo $campoDevolucion?>" style="width:720px;">
            	<div class="form">
                	<form action="javascript:void(0)" method="post" name="formbusquedamultimedia" id="formbusquedamultimedia">
                		<div class="ancho_10">
                        <div class="ancho_2">
                            <label>Buscar</label>
                        </div>
                        <div class="ancho_5">
                            <input type="text" name="criteriobusqueda" class="full" value="" />
                        </div>
                        <div class="ancho_1">&nbsp;</div>
                        <div class="ancho_2">
                            <div class="menubarra">
                                <ul>
                                    <li><div class="ancho_boton aire"><a class="boton azul" href="javascript:void(0)" onclick="gridReloadMultimedia()">Buscar</a></div></li>
                                </ul>
                            </div>        
                        </div>
                        <div class="clear aire_vertical">&nbsp;</div>
                    </div>
                    	<div class="clear aire_vertical">&nbsp;</div>
                	</form>
                </div>
            	<table id="TableMultimedia_<?php  echo $campoDevolucion?>"></table>
                <div id="pagermultimedia_<?php  echo $campoDevolucion?>"></div>
            </div>
        </div>
         <form name="DevolverCampo" id="DevolverCampo" method="post">
        	<input type="hidden" name="campodevolucion" id="campodevolucion" value="<?php  echo $campoDevolucion?>" />
        </form>	

   </div>     
</div>