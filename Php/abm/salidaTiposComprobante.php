<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

header('Content-Type: application/json; charset=utf-8');

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->query("SELECT codigoTipo, DescripcionTipo FROM tipos_comprobante ORDER BY codigoTipo");
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $dbh = null;

    $respuesta = new stdClass();
    $respuesta->tiposComprobante = $tipos;
    echo json_encode($respuesta, JSON_INVALID_UTF8_SUBSTITUTE);
} catch (PDOException $e) {
    error_log("Error en salidaTipos: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
