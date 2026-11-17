<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

header('Content-Type: application/json; charset=utf-8');

// --- Orden con whitelist (proteccion contra inyeccion SQL) ---
$orden = isset($_POST['orden']) && !empty($_POST['orden']) ? $_POST['orden'] : 'NroComprobante';
$columnasValidas = ['NroComprobante','NroDeFacturaImputada','codigoTipo','CodCliente','fechaComprobante','TotalNetoComprobante'];
if (!in_array($orden, $columnasValidas)) {
    $orden = 'NroComprobante';
}

// --- Filtros ---
$f_nroComp = isset($_POST['f_nroComprobante']) ? $_POST['f_nroComprobante'] : '';
$f_nroFact = isset($_POST['f_nroFactura'])      ? $_POST['f_nroFactura']     : '';
$f_tipo    = isset($_POST['f_codigoTipo'])       ? $_POST['f_codigoTipo']    : '';
$f_cliente = isset($_POST['f_codCliente'])       ? $_POST['f_codCliente']    : '';
$f_fecha   = isset($_POST['f_fecha'])            ? $_POST['f_fecha']         : '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT nc.NroComprobante,
                   nc.NroDeFacturaImputada,
                   nc.codigoTipo,
                   tc.DescripcionTipo,
                   nc.CodCliente,
                   nc.Observaciones,
                   nc.fechaComprobante,
                   nc.TotalNetoComprobante,
                   CASE WHEN nc.pdfComprobante IS NOT NULL THEN 1 ELSE 0 END AS tienePdf
            FROM notas_credito nc
            INNER JOIN tipos_comprobante tc ON nc.codigoTipo = tc.codigoTipo
            WHERE nc.NroComprobante       LIKE CONCAT('%', :nroComp, '%')
              AND nc.NroDeFacturaImputada  LIKE CONCAT('%', :nroFact, '%')
              AND nc.codigoTipo            LIKE CONCAT('%', :tipo,    '%')
              AND nc.CodCliente            LIKE CONCAT('%', :cliente, '%')
              AND nc.fechaComprobante      LIKE CONCAT('%', :fecha,   '%')
            ORDER BY $orden";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nroComp', $f_nroComp, PDO::PARAM_STR);
    $stmt->bindParam(':nroFact', $f_nroFact, PDO::PARAM_STR);
    $stmt->bindParam(':tipo',    $f_tipo,    PDO::PARAM_STR);
    $stmt->bindParam(':cliente', $f_cliente, PDO::PARAM_STR);
    $stmt->bindParam(':fecha',   $f_fecha,   PDO::PARAM_STR);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();

    $notas = [];
    while ($fila = $stmt->fetch()) {
        $obj = new stdClass();
        $obj->NroComprobante       = $fila['NroComprobante'];
        $obj->NroDeFacturaImputada = $fila['NroDeFacturaImputada'];
        $obj->codigoTipo           = $fila['codigoTipo'];
        $obj->DescripcionTipo      = $fila['DescripcionTipo'];
        $obj->CodCliente           = $fila['CodCliente'];
        $obj->Observaciones        = $fila['Observaciones'];
        $obj->fechaComprobante     = $fila['fechaComprobante'];
        $obj->TotalNetoComprobante = (float)$fila['TotalNetoComprobante'];
        $obj->tienePdf             = (int)$fila['tienePdf'];
        array_push($notas, $obj);
    }

    $respuesta = new stdClass();
    $respuesta->notasCredito = $notas;
    $respuesta->cuenta       = count($notas);
    $dbh = null;

    echo json_encode($respuesta, JSON_INVALID_UTF8_SUBSTITUTE);
} catch (PDOException $e) {
    error_log("Error en salidaJson: " . $e->getMessage());
    echo json_encode(['error' => 'Error al consultar la base de datos: ' . $e->getMessage()]);
}
