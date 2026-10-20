<?php
$host   = "b6e1dnzjhajzfextbbcs-mysql.services.clever-cloud.com";
$db     = "b6e1dnzjhajzfextbbcs";
$user   = "ubfjuywlm1htsjy0";
$pass   = "yzg4Hjq3yudhhhfTdyJ2";

try {
    $dbh = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $dbh->prepare("SELECT codigoTipo, DescripcionTipo FROM tipos_comprobante ORDER BY codigoTipo");
    $stmt->execute();

    $tiposComprobante = [];
    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $obj = new stdClass();
        $obj->codigoTipo      = $fila['codigoTipo'];
        $obj->DescripcionTipo = $fila['DescripcionTipo'];
        $tiposComprobante[]   = $obj;
    }

    $objResultado = new stdClass();
    $objResultado->cantidad         = count($tiposComprobante);
    $objResultado->tiposComprobante = $tiposComprobante;

    header("Content-Type: application/json");
    echo json_encode($objResultado);

} catch (PDOException $e) {
    $archivo = fopen("./errores.log", "a");
    fwrite($archivo, date("Y-m-d H:i") . " - " . $e->getMessage() . "\n");
    fclose($archivo);

    header("Content-Type: application/json");
    echo json_encode(["error" => "Error de base de datos"]);
}
