<?php
include "./libreria.inc";

$log  = isset($_POST['login'])    ? trim($_POST['login'])    : '';
$pass = isset($_POST['password']) ? trim($_POST['password']) : '';

$usuarioValidado = autenticacion($log, $pass);

if (!$usuarioValidado) {
    header("Location: ./formularioDeLogin.html?error=1");
    exit();
}

session_start();
$_SESSION['idsesion']      = session_create_id();
$_SESSION['login']         = $usuarioValidado['login'];
$_SESSION['nombreCompleto'] = $usuarioValidado['nombres'] . " " . $usuarioValidado['apellido'];

// Incrementar contador en la base de datos
include "./datosConexion.inc";
$nuevoContador = $usuarioValidado['contador'] + 1;

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE usuarios SET ContadorSesiones = :c WHERE login = :l";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':c', $nuevoContador, PDO::PARAM_INT);
    $stmt->bindParam(':l', $usuarioValidado['login'], PDO::PARAM_STR);
    $stmt->execute();
    $dbh = null;
} catch (PDOException $e) {
    $nuevoContador = $usuarioValidado['contador'];
}

$_SESSION['contador'] = $nuevoContador;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Bienvenido</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, sans-serif; background: #1b4332; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
  .tarjeta { background: white; max-width: 420px; width: 100%; border-top: 5px solid #52b788; padding: 36px 32px; box-shadow: 0 10px 32px rgba(0,0,0,0.4); }
  h2 { color: #1b4332; font-size: 1.1em; margin-bottom: 20px; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
  td { padding: 8px 10px; border-bottom: 1px solid #eee; font-size: 0.9em; }
  td:first-child { font-weight: bold; color: #2d6a4f; width: 45%; }
  .botones { display: flex; gap: 12px; }
  a.btn { display: block; flex: 1; text-align: center; padding: 11px; text-decoration: none; font-weight: bold; font-size: 0.9em; }
  a.btn-primario { background: #2d6a4f; color: white; }
  a.btn-primario:hover { background: #1b4332; }
  a.btn-secundario { background: #eee; color: #333; }
  a.btn-secundario:hover { background: #ddd; }
</style>
</head>
<body>
<div class="tarjeta">
  <h2>Ingreso exitoso al sistema</h2>
  <table>
    <tr><td>Usuario</td><td><?php echo htmlspecialchars($_SESSION['nombreCompleto']); ?></td></tr>
    <tr><td>Login</td><td><?php echo htmlspecialchars($_SESSION['login']); ?></td></tr>
    <tr><td>ID de sesion</td><td><?php echo htmlspecialchars($_SESSION['idsesion']); ?></td></tr>
    <tr><td>Contador de sesiones</td><td><?php echo htmlspecialchars($_SESSION['contador']); ?></td></tr>
  </table>
  <div class="botones">
    <a href="./index.php" class="btn btn-primario">Entrar a la Aplicacion</a>
    <a href="./destruirSesion.php" class="btn btn-secundario">Cerrar Sesion</a>
  </div>
</div>
</body>
</html>
