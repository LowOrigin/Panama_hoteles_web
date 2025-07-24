<?php
header('Content-Type: application/json');
require_once("Registro.php");
require_once("SanitizarEntrada.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$nombre   = SanitizarEntrada::limpiarCadena($_POST['nombre'] ?? '');
$apellido = SanitizarEntrada::limpiarCadena($_POST['apellido'] ?? '');
$usuario  = SanitizarEntrada::limpiarCadena($_POST['usuario'] ?? '');
$correo   = filter_var($_POST['correo'] ?? '', FILTER_VALIDATE_EMAIL);
$clave    = SanitizarEntrada::limpiarCadena($_POST['pasword'] ?? '');
$sexo     = SanitizarEntrada::limpiarCadena($_POST['sexo'] ?? '');

if (!$correo) {
    echo json_encode(['success' => false, 'message' => 'Correo inválido.']);
    exit;
}

$registro = new Registro($nombre, $apellido, $usuario, $correo, $clave, $sexo);
$resultado = $registro->registrarUsuario();

echo json_encode($resultado);
?>
