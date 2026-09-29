<?php
header('Content-Type: application/json; charset=utf-8');

$nroComprobante     = $_POST['NroComprobante'] ?? '';
$nroFactura         = $_POST['NroDeFacturaImputada'] ?? '';
$tipoComprobante    = $_POST['TipoComprobante'] ?? '';
$codCliente         = $_POST['CodCliente'] ?? '';
$observaciones      = $_POST['Observaciones'] ?? '';
$fecha              = $_POST['fechaComprobante'] ?? '';
$total              = $_POST['TotalNetoComprobante'] ?? 0;

$aErrores = [];
if ($nroComprobante === '')  $aErrores[] = "NroComprobante es obligatorio";
if ($nroFactura === '')      $aErrores[] = "NroDeFacturaImputada es obligatorio";
if ($tipoComprobante === '') $aErrores[] = "TipoComprobante es obligatorio";
if ($codCliente === '')      $aErrores[] = "CodCliente es obligatorio";
if ($fecha === '')           $aErrores[] = "fechaComprobante es obligatorio";

$respuesta = new stdClass();

if (count($aErrores) > 0) {
    $respuesta->estado = "ERROR";
    $respuesta->mensaje = "Validación fallida: " . implode(", ", $aErrores);
    $respuesta->errores = $aErrores;
} else {
    $respuesta->estado = "OK";
    $respuesta->mensaje = "Nota de crédito " . $nroComprobante . " recibida correctamente en el servidor.";
    $respuesta->datosRecibidos = $_POST;
}

echo json_encode($respuesta);
