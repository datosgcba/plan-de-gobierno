<?php
$zonamodulocod="";
if ($dataPostSend['zonamodulocod']!="")
	$zonamodulocod = $dataPostSend['zonamodulocod'];
	
if ($zonamodulocod!="")
{
	$archivo = "modulo_ogpbannerscaja_".$zonamodulocod.".json";
	if(file_exists(PUBLICA."json/".$archivo))
	{
		$string = file_get_contents(PUBLICA."json/".$archivo);
		$arrayJson = json_decode($string,true);
		$array = FuncionesPHPLocal::ConvertiraUtf8($arrayJson);
	}
	 
	 
	$cantidad = 0;
	shuffle($array);
    foreach ($array as $banner)
    {   
        ?>
         <div class="col-md-4 col-sm-6 col-xs-12 bannerscajas">
               <figure class="caja_link" style="background-image:url('<? echo DOMINIO_SERVIDOR_MULTIMEDIA."banners/".$banner["img"];?>">
                   <h3><? echo $banner["titulo"]?></h3>
                   <? if ($banner["bajada"]!=""){?>
                         <p><? echo FuncionesPHPLocal::HtmlspecialcharsBigtree($banner["bajada"],ENT_QUOTES)?></p>
                    <? }?> 
                    <a class="btn btn-primary btn-xl" href="<? echo $banner["link"]?>" target="_blank"><? echo $banner["linktexto"]?></a>
               </figure>
         </div>
        <?
        $cantidad++;
     }
}?>