<?php
/* Conexión a la base de datos con PDO */
$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

try {
    $dbh = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /* Traer todos los tipos de comprobante */
    $stmt = $dbh->prepare("SELECT codigoTipo, DescripcionTipo FROM tipos_comprobante ORDER BY codigoTipo");
    $stmt->execute();

    $tiposComprobante = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $obj = new stdClass();
        $obj->codigoTipo     = $fila['codigoTipo'];
        $obj->DescripcionTipo = $fila['DescripcionTipo'];
        $tiposComprobante[] = $obj;
    }

    $objResultado = new stdClass();
    $objResultado->cantidad         = count($tiposComprobante);
    $objResultado->tiposComprobante = $tiposComprobante;

    header("Content-Type: application/json");
    echo json_encode($objResultado);

} catch (PDOException $e) {
    /* Registrar el error en el log */
    $archivo = fopen("./errores.log", "a");
    fwrite($archivo, date("Y-m-d H:i") . " - " . $e->getMessage() . "\n");
    fclose($archivo);

    header("Content-Type: application/json");
    echo json_encode(["error" => "Error de base de datos"]);
}
