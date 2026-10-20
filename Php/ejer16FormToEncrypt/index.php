<?php
/* Si llegaron datos por POST, calcular los hashes */
if (isset($_POST['clave'])) {
    $clave = $_POST['clave'];
    $hashMd5  = md5($clave);
    $hashSha1 = sha1($clave);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ejer16 - Encriptación MD5 y SHA1</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, sans-serif; background: #f0f0f0; color: #222; padding: 40px 24px; }
  h1 { font-size: 1.2em; color: #1b4332; border-bottom: 2px solid #52b788; padding-bottom: 6px; margin-bottom: 20px; }
  form { background: white; border: 1px solid #ccc; padding: 24px; max-width: 500px; margin-bottom: 24px; }
  label { display: block; font-size: 0.88em; font-weight: bold; color: #2d6a4f; margin-bottom: 6px; }
  input[type="text"] { width: 100%; padding: 8px 10px; border: 1px solid #ccc; font-size: 0.9em; margin-bottom: 14px; }
  button { background: #2d6a4f; color: white; border: none; padding: 10px 24px; font-size: 0.9em; cursor: pointer; }
  button:hover { background: #1b4332; }
  .resultado { background: white; border: 1px solid #ccc; padding: 20px; max-width: 500px; }
  .resultado h2 { font-size: 1em; color: #1b4332; margin-bottom: 14px; }
  .fila { margin-bottom: 10px; font-size: 0.88em; }
  .fila strong { color: #2d6a4f; display: block; margin-bottom: 3px; }
  .hash { font-family: monospace; background: #f4faf6; padding: 6px 10px; border: 1px solid #d8f3dc; word-break: break-all; }
  .volver { display: block; margin-top: 20px; font-size: 0.85em; color: #1b4332; text-decoration: none; }
  .volver:hover { text-decoration: underline; }
</style>
</head>
<body>

<h1>Ejer16 — Encriptación MD5 y SHA1</h1>

<!-- Formulario de ingreso de la clave -->
<form method="POST">
  <label for="clave">Ingresá una clave para encriptar:</label>
  <input type="text" id="clave" name="clave" placeholder="Escribí algo...">
  <button type="submit">Encriptar</button>
</form>

<?php if (isset($hashMd5)): ?>
<!-- Resultado: se muestra solo cuando llegaron datos por POST -->
<div class="resultado">
  <h2>Resultado para: <em><?= htmlspecialchars($clave) ?></em></h2>
  <div class="fila">
    <strong>MD5 (128 bits / 16 pares hex):</strong>
    <span class="hash"><?= $hashMd5 ?></span>
  </div>
  <div class="fila">
    <strong>SHA1 (160 bits / 20 pares hex):</strong>
    <span class="hash"><?= $hashSha1 ?></span>
  </div>
</div>
<?php endif; ?>

<a class="volver" href="../index.html">← Volver al índice de PHP</a>

</body>
</html>
