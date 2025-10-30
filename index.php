<?php
require_once __DIR__ . '/app/core/Controller.php';

$controllerName = $_GET['controller'] ?? 'productos';
$action = $_GET['action'] ?? 'index';

$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile = __DIR__ . '/app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
  http_response_code(404);
  echo "Controlador no encontrado.";
  exit;
}
require_once $controllerFile;

$controller = new $controllerClass();
if (!method_exists($controller, $action)) {
  http_response_code(404);
  echo "Acción no encontrada.";
  exit;
}
$controller->$action();
