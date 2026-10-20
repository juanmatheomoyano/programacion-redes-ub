<?php
$host   = "b6e1dnzjhajzfextbbcs-mysql.services.clever-cloud.com";
$db     = "b6e1dnzjhajzfextbbcs";
$user   = "ubfjuywlm1htsjy0";
$pass   = "yzg4Hjq3yudhhhfTdyJ2";

$filtroNro     = $_POST['NroComprobante']       ?? '';
$filtroFactura = $_POST['NroDeFacturaImputada']  ?? '';
$filtroTipo    = $_POST['codigoTipo']            ?? '';
$filtroCliente = $_POST['CodCliente']            ?? '';
$filtroFecha   = $_POST['fechaComprobante']      ?? '';
$orden         = $_POST['orden']                 ?? 'NroComprobante';

$columnasPermitidas = ['NroComprobante', 'NroDeFacturaImputada', 'codigoTipo', 'CodCliente', 'fechaComprobante', 'TotalNetoComprobante'];
if (!in_array($orden, $columnasPermitidas)) {
    $orden = 'NroComprobante';
}

try {
    $dbh = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql  = "SELECT * FROM notas_credito WHERE ";
    $sql .= "NroComprobante       LIKE CONCAT('%',:NroComprobante,'%') AND ";
    $sql .= "NroDeFacturaImputada LIKE CONCAT('%',:NroDeFacturaImputada,'%') AND ";
    $sql .= "codigoTipo           LIKE CONCAT('%',:codigoTipo,'%') AND ";
    $sql .= "CodCliente           LIKE CONCAT('%',:CodCliente,'%') AND ";
    $sql .= "fechaComprobante     LIKE CONCAT('%',:fechaComprobante,'%') ";
    $sql .= "ORDER BY $orden";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':NroComprobante',       $filtroNro);
    $stmt->bindParam(':NroDeFacturaImputada', $filtroFactura);
    $stmt->bindParam(':codigoTipo',           $filtroTipo);
    $stmt->bindParam(':CodCliente',           $filtroCliente);
    $stmt->bindParam(':fechaComprobante',     $filtroFecha);
    $stmt->execute();

    $notasCredito = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $obj = new stdClass();
        $obj->NroComprobante        = $fila['NroComprobante'];
        $obj->NroDeFacturaImputada  = $fila['NroDeFacturaImputada'];
        $obj->codigoTipo            = $fila['codigoTipo'];
        $obj->CodCliente            = $fila['CodCliente'];
        $obj->Observaciones         = $fila['Observaciones'];
        $obj->fechaComprobante      = $fila['fechaComprobante'];
        $obj->TotalNetoComprobante  = $fila['TotalNetoComprobante'];
        $notasCredito[]             = $obj;
    }

    $objResultado = new stdClass();
    $objResultado->cantidad     = count($notasCredito);
    $objResultado->notasCredito = $notasCredito;

    header("Content-Type: application/json");
    echo json_encode($objResultado);

} catch (PDOException $e) {
    $archivo = fopen("./errores.log", "a");
    fwrite($archivo, date("Y-m-d H:i") . " - " . $e->getMessage() . "\n");
    fclose($archivo);

    header("Content-Type: application/json");
    echo json_encode(["error" => "Error de base de datos"]);
}
