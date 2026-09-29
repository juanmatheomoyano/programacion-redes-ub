<?php
header('Content-Type: application/json; charset=utf-8');

$aNotasCredito = [];

$nc1 = new stdClass();
$nc1->NroComprobante = "NC-0001";
$nc1->NroDeFacturaImputada = "FC-5501";
$nc1->TipoComprobante = "01";
$nc1->CodCliente = "CLI-100";
$nc1->Observaciones = "Ajuste por diferencia de tipo de cambio dólar";
$nc1->fechaComprobante = "2025-03-10";
$nc1->TotalNetoComprobante = 45000.00;
$aNotasCredito[] = $nc1;

$nc2 = new stdClass();
$nc2->NroComprobante = "NC-0002";
$nc2->NroDeFacturaImputada = "FC-5523";
$nc2->TipoComprobante = "02";
$nc2->CodCliente = "CLI-200";
$nc2->Observaciones = "Bonificación por volumen de compra trimestral";
$nc2->fechaComprobante = "2025-03-15";
$nc2->TotalNetoComprobante = 12500.50;
$aNotasCredito[] = $nc2;

$nc3 = new stdClass();
$nc3->NroComprobante = "NC-0003";
$nc3->NroDeFacturaImputada = "FC-5548";
$nc3->TipoComprobante = "03";
$nc3->CodCliente = "CLI-100";
$nc3->Observaciones = "Devolución parcial de mercadería defectuosa";
$nc3->fechaComprobante = "2025-03-22";
$nc3->TotalNetoComprobante = 8750.00;
$aNotasCredito[] = $nc3;

$nc4 = new stdClass();
$nc4->NroComprobante = "NC-0004";
$nc4->NroDeFacturaImputada = "FC-5601";
$nc4->TipoComprobante = "01";
$nc4->CodCliente = "CLI-300";
$nc4->Observaciones = "Diferencia de cambio por factura en moneda extranjera";
$nc4->fechaComprobante = "2025-04-05";
$nc4->TotalNetoComprobante = 31200.75;
$aNotasCredito[] = $nc4;

$nc5 = new stdClass();
$nc5->NroComprobante = "NC-0005";
$nc5->NroDeFacturaImputada = "FC-5620";
$nc5->TipoComprobante = "02";
$nc5->CodCliente = "CLI-200";
$nc5->Observaciones = "Descuento especial por acuerdo comercial anual";
$nc5->fechaComprobante = "2025-04-12";
$nc5->TotalNetoComprobante = 19800.00;
$aNotasCredito[] = $nc5;

if (isset($_GET['codCliente']) && $_GET['codCliente'] !== '') {
    $filtro = $_GET['codCliente'];
    $aNotasCredito = array_values(array_filter($aNotasCredito, function($nc) use ($filtro) {
        return $nc->CodCliente === $filtro;
    }));
}

$objRespuesta = new stdClass();
$objRespuesta->notasCredito = $aNotasCredito;
$objRespuesta->totalRegistros = count($aNotasCredito);
$objRespuesta->ipServidor = $_SERVER['SERVER_ADDR'];
$objRespuesta->metodoRequerimiento = $_SERVER['REQUEST_METHOD'];

echo json_encode($objRespuesta);
