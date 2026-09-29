<?php
header('Content-Type: application/json; charset=utf-8');

$aTodasLasVariables = [];
foreach ($_SERVER as $clave => $valor) {
    $entrada = new stdClass();
    $entrada->clave = $clave;
    $entrada->valor = $valor;
    $aTodasLasVariables[] = $entrada;
}

$objRespuesta = new stdClass();
$objRespuesta->SERVER_NAME      = $_SERVER['SERVER_NAME']     ?? 'N/A';
$objRespuesta->SERVER_ADDR      = $_SERVER['SERVER_ADDR']     ?? 'N/A';
$objRespuesta->REMOTE_ADDR      = $_SERVER['REMOTE_ADDR']     ?? 'N/A';
$objRespuesta->REQUEST_METHOD   = $_SERVER['REQUEST_METHOD']  ?? 'N/A';
$objRespuesta->REQUEST_URI      = $_SERVER['REQUEST_URI']     ?? 'N/A';
$objRespuesta->HTTP_USER_AGENT  = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
$objRespuesta->todasLasVariables = $aTodasLasVariables;

echo json_encode($objRespuesta);
