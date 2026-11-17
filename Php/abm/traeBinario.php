<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

header('Content-Type: application/json; charset=utf-8');

$nroComp = isset($_GET['nroComprobante']) ? trim($_GET['nroComprobante']) : '';

if (empty($nroComp)) {
    echo json_encode(['error' => 'Falta el identificador del comprobante']);
    exit();
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT pdfComprobante FROM notas_credito WHERE NroComprobante = :nroComp";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nroComp', $nroComp, PDO::PARAM_STR);
    $stmt->execute();
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);
    $dbh = null;

    if ($fila && !empty($fila['pdfComprobante'])) {
        $obj = new stdClass();
        $obj->documentoPdf = base64_encode($fila['pdfComprobante']);
        echo json_encode($obj, JSON_INVALID_UTF8_SUBSTITUTE);
    } else {
        echo json_encode(['error' => 'Este comprobante no tiene PDF adjunto']);
    }
} catch (PDOException $e) {
    error_log("Error en traeBinario: " . $e->getMessage());
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}
