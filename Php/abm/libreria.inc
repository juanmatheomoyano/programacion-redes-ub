<?php
function autenticacion($login, $passwordPlana) {
    include "./datosConexion.inc";
    $passwordHash = hash('sha256', $passwordPlana);

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT idUsuario, login, apellido, nombres, contador
                FROM usuarios
                WHERE login = :login AND password = :password";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':login',    $login,        PDO::PARAM_STR);
        $stmt->bindParam(':password', $passwordHash, PDO::PARAM_STR);
        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        return $fila ? $fila : false;
    } catch (PDOException $e) {
        error_log("Error en autenticacion: " . $e->getMessage());
        return false;
    }
}
