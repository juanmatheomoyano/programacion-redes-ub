<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

$respuesta_estado = "--- RESPUESTA DEL SERVIDOR A LA MODIFICACION ---\n";

$nroComp = isset($_POST['NroComprobante'])       ? trim($_POST['NroComprobante'])       : '';
$nroFact = isset($_POST['NroDeFacturaImputada'])  ? trim($_POST['NroDeFacturaImputada']) : '';
$codTipo = isset($_POST['codigoTipo'])            ? trim($_POST['codigoTipo'])           : '';
$codCli  = isset($_POST['CodCliente'])            ? trim($_POST['CodCliente'])           : '';
$obs     = isset($_POST['Observaciones'])         ? trim($_POST['Observaciones'])        : '';
$fecha   = isset($_POST['fechaComprobante'])      ? trim($_POST['fechaComprobante'])     : '';
$total   = isset($_POST['TotalNetoComprobante'])  ? trim($_POST['TotalNetoComprobante']) : '0';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $respuesta_estado .= "1. Conexion con base de datos: EXITOSA!\n";

    // --- Etapa 2: Modificacion de datos simples ---
    $sqlModi = "UPDATE notas_credito SET
                    NroDeFacturaImputada  = :nroFact,
                    codigoTipo            = :codTipo,
                    CodCliente            = :codCli,
                    Observaciones         = :obs,
                    fechaComprobante      = :fecha,
                    TotalNetoComprobante  = :total
                WHERE NroComprobante = :nroComp";

    $stmt = $dbh->prepare($sqlModi);
    $respuesta_estado .= "2. Preparacion sentencia UPDATE: EXITOSA!\n";

    $stmt->bindParam(':nroFact', $nroFact, PDO::PARAM_STR);
    $stmt->bindParam(':codTipo', $codTipo, PDO::PARAM_STR);
    $stmt->bindParam(':codCli',  $codCli,  PDO::PARAM_STR);
    $stmt->bindParam(':obs',     $obs,     PDO::PARAM_STR);
    $stmt->bindParam(':fecha',   $fecha,   PDO::PARAM_STR);
    $stmt->bindParam(':total',   $total,   PDO::PARAM_STR);
    $stmt->bindParam(':nroComp', $nroComp, PDO::PARAM_STR);
    $respuesta_estado .= "3. Vinculacion (Binding) de parametros: EXITOSA!\n";

    $stmt->execute();
    $respuesta_estado .= "4. Ejecucion UPDATE (datos simples): EXITOSA!\n";

    // --- Etapa 5: Actualizacion opcional del binario PDF ---
    if (isset($_FILES['documentoPdf']) && !empty($_FILES['documentoPdf']['name'])) {
        $contenidoPdf = file_get_contents($_FILES['documentoPdf']['tmp_name']);
        $sqlBin = "UPDATE notas_credito SET pdfComprobante = :pdf WHERE NroComprobante = :nroComp";
        $stmtBin = $dbh->prepare($sqlBin);
        $stmtBin->bindParam(':pdf',     $contenidoPdf, PDO::PARAM_LOB);
        $stmtBin->bindParam(':nroComp', $nroComp,      PDO::PARAM_STR);
        $stmtBin->execute();
        $respuesta_estado .= "5. Actualizacion de archivo binario PDF: EXITOSA!\n";
    } else {
        $respuesta_estado .= "5. Archivo PDF: No se envio archivo nuevo (binario anterior conservado).\n";
    }

    $dbh = null;
    $respuesta_estado .= "Modificacion completada con exito.\n";
    echo $respuesta_estado;
} catch (PDOException $e) {
    error_log("Error en modi: " . $e->getMessage());
    $respuesta_estado .= "Error durante la modificacion: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
}
