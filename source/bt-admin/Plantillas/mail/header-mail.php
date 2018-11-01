<?php
function headermail($titulo,$dominio = DOMINIOPORTAL)
{
		$html = '<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>'.$titulo.'</title>
	</head>
	<body style="margin: 0;">
		<div style="padding: 10px; text-align: center; background-color: #fcda59; color: #000000;">
			<img src="'.$dominio.'bt-admin/Plantillas/mail/img/logo.png" alt="Vamos Buenos Aires" />
		</div>
		
		<div style="font-family:Helvetica, Arial, sans-serif;">
			<div style="max-width: 630px; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;">
				<br/>
				<!--  Fin del header -->';
	return $html;
}?>