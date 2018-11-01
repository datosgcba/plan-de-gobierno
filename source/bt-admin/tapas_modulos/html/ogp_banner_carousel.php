<?

$popup_mensaje = "";
$popup_titulo = "";
$popup_boton_muestra = "";
$popup_boton = "";
$popup_boton_ubic = "left";

$oBanner = new cBanners($vars['conexion']);
$objDataModel = json_decode($vars['modulodata']);

if (isset($objDataModel->popup_titulo))
	$popup_titulo  = $objDataModel->popup_titulo;
if (isset($objDataModel->popup_mensaje))
	$popup_mensaje  = $objDataModel->popup_mensaje;
if (isset($objDataModel->popup_boton_muestra))
	$popup_boton_muestra  = $objDataModel->popup_boton_muestra;
if (isset($objDataModel->popup_boton))
	$popup_boton  = $objDataModel->popup_boton;
if (isset($objDataModel->popup_boton_ubic))
	$popup_boton_ubic  = $objDataModel->popup_boton_ubic;


?>
        
<div class="ogp_banners_carousel tap_modules" id="module_<? echo $vars['zonamodulocod']?>" <? echo  $vars['mouseaction']?>>
	<? echo $vars['htmledit']?>
	<div id="carousel-ogp_<? echo $vars['zonamodulocod']?>" class="carousel slide carousel-ogp" data-ride="carousel"  data-interval="5000"> 
    	<!-- Indicators -->
        <ol class="carousel-indicators">
		<? 
		$cantidad = 0;
        foreach ($objDataModel->bannercod as $bannercod)
        {
			$active="";
			if ($cantidad==0)
				$active="active";
			?>
            <li data-target="#carousel-ogp_<? echo $vars['zonamodulocod']?>" data-slide-to="0" class="<? echo $active?>"></li>
            <?
			$cantidad++;
		}?>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
			<? 
            $cantidad = 0;
            foreach ($objDataModel->bannercod as $bannercod)
            {
				$active="";
				if ($cantidad==0)
					$active="active ";
				
                $datosbusqueda['bannercod'] = $bannercod;
                if(!$oBanner->BuscarBannerxCodigo($datosbusqueda,$resultado,$numfilas))
                   return false;
                                
                $datosbanner = $vars['conexion']->ObtenerSiguienteRegistro($resultado);
                $nombrearchivo = $datosbanner['bannerarchubic'];
                $imagenNormal = str_replace("banners/","",Multimedia::GetImagenStatic(1400, 600, "banners/".$nombrearchivo, 1, true));
                ?>
                <div class="item <? echo $active?>">
                	<img src="<? echo DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$imagenNormal;?>" alt="<? echo utf8_decode($objDataModel->BannerTitulo->$bannercod)?>"/>
                     <div class="carousel-caption">
                        <h1><? echo utf8_decode($objDataModel->BannerTitulo->$bannercod)?></h1>
                        <p class="lead"><? echo utf8_decode($objDataModel->BannerTexto->$bannercod)?></p>
                    </div>
                </div>
                <?
                $cantidad++;
            }
            ?>
        </div>
         <!-- Controls -->
        <a class="left carousel-control" href="#carousel-ogp_<? echo $vars['zonamodulocod']?>" role="button" data-slide="prev">
           <span class="glyphicon glyphicon-chevron-left"></span>
         </a>
         <a class="right carousel-control" href="#carousel-ogp_<? echo $vars['zonamodulocod']?>" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
         </a>
    </div>
	<? if($popup_boton_muestra==1){?>
        <a style="position: absolute;z-index: 999999999999999999999999999999999999;border-radius: 0;bottom: 0; <? echo $popup_boton_ubic.":0;";?>" type="button" class="btn btn-info btn-lg" title="<? echo $popup_boton;?>" onClick="levantarpopup();" href="javascript:void();"><? echo $popup_boton;?></a>
   			<div id="ModalAlta" class="modal fade">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title"><?php  echo  FuncionesPHPLocal::HtmlspecialcharsBigtree(utf8_decode($popup_titulo),ENT_QUOTES);?></h4>
                  </div>
                  <div class="modal-body">
                    <div id="DataAlta">
                        <p><?php  echo $popup_mensaje;?></p>
                    </div>
                    <div class="clearboth"></div>
                  </div>
                  <div class="modal-footer">
                  </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
            </div>
    <? } ?>
 </div>
 <script >
	function levantarpopup()
	{
		$('#ModalAlta').modal('show');
	}
</script>
