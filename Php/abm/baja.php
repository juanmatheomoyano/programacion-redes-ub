<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

$respuesta_estado = "--- RESPUESTA DEL SERVIDOR A LA BAJA ---\n";

$nroComp = isset($_POST['NroComprobante']) ? trim($_POST['NroComprobante']) : '';

if (empty($nroComp)) {
    echo $respuesta_estado . "Error: Clave primaria no recibida.\n";
    exit();
}

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $respuesta_estado .= "1. Conexion con base de datos: EXITOSA!\n";

    $sql = "DELETE FROM notas_credito WHERE NroComprobante = :nroComp";
    $stmt = $dbh->prepare($sql);
    $respuesta_estado .= "2. Preparacion sentencia DELETE: EXITOSA!\n";

    $stmt->bindParam(':nroComp', $nroComp, PDO::PARAM_STR);
    $respuesta_estado .= "3. Vinculacion (Binding) de parametros: EXITOSA!\n";

    $stmt->execute();
    $afectados = $stmt->rowCount();
    $respuesta_estado .= "4. Ejecucion DELETE: EXITOSA! Registros eliminados: $afectados\n";

    $dbh = null;
    $respuesta_estado .= "Comprobante $nroComp eliminado correctamente.\n";
    echo $respuesta_estado;
} catch (PDOException $e) {
    error_log("Error en baja: " . $e->getMessage());
    $respuesta_estado .= "Error en la baja: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
}
