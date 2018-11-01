<?php 
class cCalendario
{
	

	static function MostrarCalendario ($year,$mes,$finDeSemana=1,$mostrarDiasNulos=1,$nivelH=2) {
		
		if (strlen($year)!=4) {$year=date('Y');}
		if (($mes<1 or $mes>12) or (strlen($mes)<1 or strlen($mes)>2)) {$year=date('n');}
		
		// Listados: d�as de la semana, letra inicial de los d�as de la semana, y meses
		$dias = array('Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado','Domingo');
		$diasAbbr = array('L','M','M','J','V','S','D');
		$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiempre','Octubre','Noviembre','Diciembre');
		
		// Se sacan valores que se utilizar�n m�s adelante
		$diaInicial = gmmktime(0,0,0,$mes,1,$year);  // Primer d�a del mes dado
		$diasNulos = (date("N",$diaInicial))-1; // Con 'N' la semana empieza en Lunes. Con 'w', en domingo
			if($diasNulos<0){$diasNulos = 7-abs($diasNulos);}
		$diasEnMes = date("t",$diaInicial); // N�mero de d�as del mes dado
		
		// Se abre la capa contenedora y se genera el encabezado del bloque de calendario
		$html = '<div id="calendario">';
		$html .= '<h'.$nivelH.' class="encabezadoCalendario">Calendario</h'.$nivelH.'>';
		
		// P�rrafos con la fecha actual y la fecha seleccionada
		$html .= '<p>Fecha actual: '.date('j').' de '.$meses[(intval(date('n'))-1)].' de '.date('Y').'</p>';
		$html .= '<p>Fecha seleccionada: ';
		if (isset($_GET['dia'])) {$html .= ''.$_GET['dia'].' de ';} // El d�a solo sale si se ha definido previamente en el par�metro 'dia' de la URL
		$html .= ''.$meses[($mes-1)].' de '.$year.'</p>';
		$html .= '<div class="tabla">';
		
		
		// Enlaces al mes anterior y al siguiente
		$html .= '<p>Navegaci�n por meses:</p>';
		$html .= '<ul id="calNavMeses">';
		$enlaceAnterior1 = gmmktime(0,0,0,($mes-1),1,$year);
		$mesAnterior = date('n',$enlaceAnterior1);
		$yearMesAnterior = date('Y',$enlaceAnterior1);
		$enlaceSiguiente1 = gmmktime(0,0,0,($mes+1),1,$year);
		$mesSiguiente = date('n',$enlaceSiguiente1);
		$yearMesSiguiente = date('Y',$enlaceSiguiente1);
		$html .= '<li class="anterior"><a href="?mes='.$mesAnterior.'&amp;ano='.$yearMesAnterior.'"><span>Mes anterior ('.$meses[($mesAnterior-1)].')</span></a></li>';
		$html .= '<li class="siguiente"><a href="?mes='.$mesSiguiente.'&amp;ano='.$yearMesSiguiente.'"><span>Mes siguiente ('.$meses[($mesSiguiente-1)].')</span></a></li>';
		$html .= '</ul>';
		
		// Enlaces al a�o anterior y al siguiente
		$html .= '<p>Navegaci�n por a�os:</p>';
		$html .= '<ul id="calNavYears">';
		$enlaceAnterior2 = gmmktime(0,0,0,$mes,1,($year-1));
		$yearAnterior = date('Y',$enlaceAnterior2);
		$enlaceSiguiente2 = gmmktime(0,0,0,$mes,1,($year+1));
		$yearSiguiente = date('Y',$enlaceSiguiente2);
		$html .= '<li class="anterior"><a href="?mes='.$mes.'&amp;ano='.$yearAnterior.'"><span>A�o anterior (</span>'.$yearAnterior.'<span>)</span></a></li>';
		$html .= '<li class="siguiente"><a href="?mes='.$mes.'&amp;ano='.$yearSiguiente.'"><span>A�o siguiente (</span>'.$yearSiguiente.'<span>)</span></a></li>';
		$html .= '</ul>';
		
		// Se abre la tabla que contiene el calendario
		$html .= '<table>';
		
		// T�tulo mes-a�o (elemento CAPTION)
		$mesLista = $mes-1;
		$html .= '<caption>'.$meses[$mesLista].'<span> de</span> '.$year.'</caption>';
		
		// Se definen anchuras en elementos COL
		$cl=0; $anchoCol=100/7; while ($cl<7) {$html .= '<col width="'.$anchoCol.'%" />'; $cl++;}
		
		// Fila de los d�as de la semana (elemento THEAD)
		$html .= '<thead><tr>';$d=0;
		while ($d<7) {$html .= '<th scope="col" abbr="'.$dias[$d].'">'.$diasAbbr[$d].'</th>';$d++;}
		$html .= '</tr></thead>';
		
		// Se generan los d�as nulos (d�as del mes anterior o posterior) iniciales, el TBODY y su primer TR
		$html .= '<tbody>';
		if ($diasNulos>0) {$html .= '<tr>';} // Se abre el TR solo si hay d�as nulos
		if ($diasNulos>0 and $mostrarDiasNulos==0) {$html .= '<td class="nulo" colspan="'.$diasNulos.'"></td>';} // Se hace un TD en blanco con el ancho seg�n los d�a nulos que haya
		if ($mostrarDiasNulos==1) { // Generaci�n de los TD con d�as nulos si est� activado que se muestren
			$dni=$diasNulos;$i=0;
			while ($i<$diasNulos) {
				$enSegundosNulo = gmmktime(0,0,0,$mes,(1-$dni),$year);
				$dmNulo = date('j',$enSegundosNulo);
				$idFechaNulo = 'cal-'.date('Y-m-d',$enSegundosNulo);
				$html .= '<td id="'.$idFechaNulo.'" class="diaNulo"><span class="dia"><span class="enlace">'.$dmNulo.'</span></span></td>';
				$dni--;
				$i++;
			}
		}
		
		
		
		// Se generan los TD con los d�as del mes
		$dm=1;$x=0;$ds=$diasNulos+1;
		while ($dm<=$diasEnMes) {
			if(($x+$diasNulos)%7==0 and $x!=0) {$html .= '</tr>';} // Se evita el cierre del TR si no hay d�as nulos iniciales
			if(($x+$diasNulos)%7==0) {$html .= '<tr>';$ds=1;}
			$enSegundosCalendario = gmmktime(0,0,0,$mes,$dm,$year); // Fecha del d�a generado en segundos
			$enSegundosActual = gmmktime(0,0,0,date('n'),date('j'),date('Y')); // Fecha actual en segundos
			
			
			if (!isset($_GET['dia'])) {$diabus=date('d');}else{$diabus=$_GET['dia'];}
			if (!isset($_GET['mes'])) {$mesbus=date('m');}else{$mesbus=$_GET['mes'];}
			if (!isset($_GET['ano'])) {$anobus=date('Y');}else{$anobus=$_GET['ano'];}
			$enSegundosSeleccionada = gmmktime(0,0,0,$mesbus,$diabus,$anobus); // Fecha seleccionada, en segundos
			$idFecha = 'cal-'.date('Y-m-d',$enSegundosCalendario);
			
			// Se generan los par�metros de la URL para el enlace del d�a
			$link_dia = date('j',$enSegundosCalendario);
			$link_mes = date('n',$enSegundosCalendario);
			$link_year = date('Y',$enSegundosCalendario);
			
			// Clases y etiquetado general para los d�as, para d�a actual y para d�a seleccionado
			$claseActual='';$tagDia='span';
			if ($enSegundosCalendario==$enSegundosActual) {$claseActual=' fechaHoy';$tagDia='strong';}
			if ($enSegundosCalendario==$enSegundosSeleccionada and isset($_GET['dia'])) {$claseActual=' fechaSeleccionada';$tagDia='em';}
			if ($enSegundosCalendario==$enSegundosActual and $enSegundosCalendario==$enSegundosSeleccionada and isset($_GET['dia'])) {$claseActual=' fechaHoy fechaSeleccionada';$tagDia='strong';}
			
			// Desactivaci�n de los d�as del fin de semana
			if (($ds<6 and $finDeSemana==0) or $finDeSemana!=0) { // Si el fin de semana est� activado, o el d�a es de lunes a viernes
				$tagEnlace='a';
				$atribEnlace='href="?dia='.$link_dia.'&amp;mes='.$link_mes.'&amp;ano='.$link_year.'"';
			} if ($ds>5 and $finDeSemana==0) { // Si el fin de semana est� desactivado y el d�a es s�bado o domingo
				$tagEnlace='span';
				$atribEnlace='';
				$paramFinde='0';
			}
			
			// Con las variables ya definidas, se crea el HTML del TD
			$html .= '<td id="'.$idFecha.'" class="'.cCalendario::calendarioClaseDia($ds).$claseActual.'"><'.$tagDia.' class="dia"><'.$tagEnlace.' class="enlace" '.$atribEnlace.'>'.$dm.'</'.$tagEnlace.'></'.$tagDia.'></td>';
			
			$dm++;$x++;$ds++;
		}
		
		// Se generan los d�as nulos finales
		$diasNulosFinales = 0;
		while((($diasEnMes+$diasNulos)%7)!=0){$diasEnMes++;$diasNulosFinales++;}
		if ($diasNulosFinales>0 and $mostrarDiasNulos==0) {$html .= '<td class="nulo" colspan="'.$diasNulosFinales.'"></td>';} // Se hace un TD en blanco con el ancho seg�n los d�a nulos que haya (si no se activa mostrar los d�as nulos)
		if ($mostrarDiasNulos==1) { // Generaci�n de d�as nulos (si se activa mostrar los d�as nulos)
			$dnf=0;
			while ($dnf<$diasNulosFinales) {
				$enSegundosNulo = gmmktime(0,0,0,($mes+1),($dnf+1),$year);
				$dmNulo = date('j',$enSegundosNulo);
				$idFechaNulo = 'cal-'.date('Y-m-d',$enSegundosNulo);
				$html .= '<td id="'.$idFechaNulo.'" class="diaNulo"><span class="dia"><span class="enlace">'.$dmNulo.'</span></span></td>';
				$dnf++;
			}
		}
		
		// Se cierra el �ltimo TR y el TBODY
		$html .= '</tr></tbody>';
		
		// Se cierra la tabla
		$html .= '</table>';
		
		// Se cierran la capa de la tabla y la capa contenedora
		$html .= '</div>';
		$html .= '</div>';
		
		// Se devuelve la variable que contiene el HTML del calendario
		return $html;
	}

	static function calendarioClaseDia ($dia) {
		switch ($dia) {
			case 1: $clase = 'lunes semana'; break;
			case 2: $clase = 'martes semana'; break;
			case 3: $clase = 'miercoles semana'; break;
			case 4: $clase = 'jueves semana'; break;
			case 5: $clase = 'viernes semana'; break;
			case 6: $clase = 'sabado finDeSemana'; break;
			case 7: $clase = 'domingo finDeSemana'; break;
		}
		return $clase;
	}
	
	
}//FIN CLASE

?>