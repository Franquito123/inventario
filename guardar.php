<?php

require_once __DIR__ . '/config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$cantidad = $_POST['cantidad'] ?? '';
$unidad_medida = trim($_POST['unidad_medida'] ?? '');

if ($nombre === '' || $cantidad === '' || $unidad_medida === '') {
    header('Location: index.php?estado=incompleto');
    exit;
}

if (mb_strlen($nombre, 'UTF-8') < 3) {
    header('Location: index.php?estado=nombre_invalido');
    exit;
}

if (!is_numeric($cantidad)) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

$cantidadInt = (int) $cantidad;

if ($cantidadInt <= 0) {
    header('Location: index.php?estado=cantidad_invalida');
    exit;
}

// Valida  la unidad de medida 
$unidades_permitidas = ['Pieza', 'Caja', 'Paquete'];
if (!in_array($unidad_medida, $unidades_permitidas, true)) {
    header('Location: index.php?estado=unidad_invalida');
    exit;
}

$sentencia = $conexion->prepare(
    'INSERT INTO productos (nombre, cantidad, unidad_medida)
     VALUES (:nombre, :cantidad, :unidad_medida)'
);

$sentencia->execute([
    'nombre' => $nombre,
    'cantidad' => $cantidadInt,
    'unidad_medida' => $unidad_medida
]);

header('Location: index.php?estado=guardado');
exit;