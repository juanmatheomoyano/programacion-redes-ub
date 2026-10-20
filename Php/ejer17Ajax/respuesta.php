<?php
/* Simular retardo de red de 3 segundos */
sleep(3);

/* Leer la clave enviada por POST */
$clave = $_POST['clave'];

/* Crear objeto con los dos hashes y devolverlo como JSON */
$objRespuesta = new stdClass();
$objRespuesta->md5  = md5($clave);
$objRespuesta->sha1 = sha1($clave);

header("Content-Type: application/json");
echo json_encode($objRespuesta);
