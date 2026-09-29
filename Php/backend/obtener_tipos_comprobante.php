<?php
header('Content-Type: application/json; charset=utf-8');

$aTipos = [];

$tipo01 = new stdClass();
$tipo01->codigo = "01";
$tipo01->descripcion = "Diferencia de Cambio";
$aTipos[] = $tipo01;

$tipo02 = new stdClass();
$tipo02->codigo = "02";
$tipo02->descripcion = "Bonificación de producto";
$aTipos[] = $tipo02;

$tipo03 = new stdClass();
$tipo03->codigo = "03";
$tipo03->descripcion = "Devolución de producto";
$aTipos[] = $tipo03;

$objRespuesta = new stdClass();
$objRespuesta->exito = true;
$objRespuesta->tiposComprobante = $aTipos;
$objRespuesta->cantidad = count($aTipos);

echo json_encode($objRespuesta);
