<?php
include "./manejoDeSesion.inc";
include "./datosConexion.inc";

$respuesta_estado = "--- RESPUESTA DEL SERVIDOR AL ALTA ---\n";

$nroComp = isset($_POST['NroComprobante'])       ? trim($_POST['NroComprobante'])       : '';
$nroFact = isset($_POST['NroDeFacturaImputada'])  ? trim($_POST['NroDeFacturaImputada']) : '';
$codTipo = isset($_POST['codigoTipo'])            ? trim($_POST['codigoTipo'])           : '';
$codCli  = isset($_POST['CodCliente'])            ? trim($_POST['CodCliente'])           : '';
$obs     = isset($_POST['Observaciones'])         ? trim($_POST['Observaciones'])        : '';
$fecha   = isset($_POST['fechaComprobante'])      ? trim($_POST['fechaComprobante'])     : '';
$total   = isset($_POST['TotalNetoComprobante'])  ? trim($_POST['TotalNetoComprobante']) : '0';

// --- Etapa 1: Conexion ---
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $respuesta_estado .= "1. Conexion con base de datos: EXITOSA!\n";
} catch (PDOException $e) {
    $respuesta_estado .= "1. Error en conexion: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
    exit();
}

// --- Etapa 2: Preparacion ---
$sqlAlta = "INSERT INTO notas_credito
                (NroComprobante, NroDeFacturaImputada, codigoTipo, CodCliente,
                 Observaciones, fechaComprobante, TotalNetoComprobante)
            VALUES
                (:nroComp, :nroFact, :codTipo, :codCli, :obs, :fecha, :total)";
try {
    $stmt = $dbh->prepare($sqlAlta);
    $respuesta_estado .= "2. Preparacion de sentencia INSERT: EXITOSA!\n";
} catch (PDOException $e) {
    $respuesta_estado .= "2. Error en prepare: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
    exit();
}

// --- Etapa 3: Binding ---
try {
    $stmt->bindParam(':nroComp', $nroComp, PDO::PARAM_STR);
    $stmt->bindParam(':nroFact', $nroFact, PDO::PARAM_STR);
    $stmt->bindParam(':codTipo', $codTipo, PDO::PARAM_STR);
    $stmt->bindParam(':codCli',  $codCli,  PDO::PARAM_STR);
    $stmt->bindParam(':obs',     $obs,     PDO::PARAM_STR);
    $stmt->bindParam(':fecha',   $fecha,   PDO::PARAM_STR);
    $stmt->bindParam(':total',   $total,   PDO::PARAM_STR);
    $respuesta_estado .= "3. Vinculacion (Binding) de parametros: EXITOSA!\n";
} catch (PDOException $e) {
    $respuesta_estado .= "3. Error en bindParam: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
    exit();
}

// --- Etapa 4: Ejecucion del alta simple ---
try {
    $stmt->execute();
    $respuesta_estado .= "4. Ejecucion INSERT (datos simples): EXITOSA!\n";
} catch (PDOException $e) {
    $respuesta_estado .= "4. Error en execute: " . $e->getMessage() . "\n";
    echo $respuesta_estado;
    exit();
}

// --- Etapa 5: Almacenamiento del binario PDF (opcional) ---
if (!isset($_FILES['documentoPdf']) || empty($_FILES['documentoPdf']['name'])) {
    $respuesta_estado .= "5. Archivo PDF: No se envio archivo (campo vacio).\n";
} else {
    $contenidoBinario = file_get_contents($_FILES['documentoPdf']['tmp_name']);
    $sqlBin = "UPDATE notas_credito SET archivo = :pdf WHERE NroComprobante = :nroComp";
    try {
        $stmtBin = $dbh->prepare($sqlBin);
        $stmtBin->bindParam(':pdf',     $contenidoBinario, PDO::PARAM_LOB);
        $stmtBin->bindParam(':nroComp', $nroComp,          PDO::PARAM_STR);
        $stmtBin->execute();
        $respuesta_estado .= "5. Almacenamiento de PDF en campo LONGBLOB: EXITOSO!\n";
    } catch (PDOException $e) {
        $respuesta_estado .= "5. Error al almacenar PDF: " . $e->getMessage() . "\n";
    }
}

$dbh = null;
$respuesta_estado .= "Alta completada con exito.\n";
echo $respuesta_estado;
