<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

$respuesta_estado = "--- RESPUESTA DEL SERVIDOR A ELIMINAR PDF ---\n";

$nroComp = isset($_POST['NroComprobante']) ? trim($_POST['NroComprobante']) : '';

if (empty($nroComp)) {
    echo $respuesta_estado . "Error: Falta el identificador del comprobante.\n";
    exit();
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $respuesta_estado .= "1. Conexion con base de datos: EXITOSA!\n";

    $sql = "UPDATE notas_credito SET archivo = NULL WHERE NroComprobante = :nroComp";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':nroComp', $nroComp, PDO::PARAM_STR);
    $stmt->execute();
    $respuesta_estado .= "2. PDF eliminado correctamente.\n";

    $dbh = null;
    echo $respuesta_estado;
} catch (PDOException $e) {
    error_log("Error en eliminarPdf: " . $e->getMessage());
    $respuesta_estado .= "Error: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
}
