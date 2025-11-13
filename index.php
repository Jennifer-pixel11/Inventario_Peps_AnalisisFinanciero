<?php
require_once __DIR__ . '/app/core/controller.php'; // OJO: es controller.php en minúsculas

$controllerName = $_GET['controller'] ?? 'panel';   // <-- panel por defecto
$action = $_GET['action'] ?? 'index';

$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile  = __DIR__ . '/app/controllers/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
  http_response_code(404);
  echo "Controlador no encontrado.";
  exit;
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
  http_response_code(500);
  echo "La clase {$controllerClass} no existe en el archivo.";
  exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
  http_response_code(404);
  echo "Acción no encontrada.";
  exit;
}

$controller->$action();
