<?php
/* Leer los campos enviados por POST y armar un objeto */
$objNota = new stdClass();
$objNota->NroComprobante        = $_POST['NroComprobante'];
$objNota->NroDeFacturaImputada  = $_POST['NroDeFacturaImputada'];
$objNota->CodCliente            = $_POST['CodCliente'];
$objNota->fechaComprobante      = $_POST['fechaComprobante'];
$objNota->TotalNetoComprobante  = $_POST['TotalNetoComprobante'];

/* Devolver el objeto como JSON */
header("Content-Type: application/json");
echo json_encode($objNota);
