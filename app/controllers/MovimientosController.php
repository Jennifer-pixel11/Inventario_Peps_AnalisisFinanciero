<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Movimiento.php';

class MovimientosController extends Controller {
  public function entrada() {
    $productos = Producto::all();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $producto_id = intval($_POST['producto_id']);
      $fecha = $_POST['fecha'];
      $cantidad = floatval($_POST['cantidad']);
      $costo = floatval($_POST['costo']);
      $nota = $_POST['nota'] ?? '';
      try {
        Movimiento::registrarEntrada($producto_id, $fecha, $cantidad, $costo, $nota);
        $msg = "Entrada registrada.";
      } catch (Exception $e) {
        $msg = "Error: " . $e->getMessage();
      }
      $this->view('movimientos/entrada', compact('productos', 'msg'));
    } else {
      $this->view('movimientos/entrada', compact('productos'));
    }
  }

  public function salida() {
    $productos = Producto::all();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $producto_id = intval($_POST['producto_id']);
      $fecha = $_POST['fecha'];
      $cantidad = floatval($_POST['cantidad']);
      $nota = $_POST['nota'] ?? '';
      try {
        $info = Movimiento::registrarSalidaPEPS($producto_id, $fecha, $cantidad, $nota);
        $msg = "Salida registrada. Costo promedio unitario: " . number_format($info['costo_unitario_promedio'], 4);
      } catch (Exception $e) {
        $msg = "Error: " . $e->getMessage();
      }
      $this->view('movimientos/salida', compact('productos', 'msg'));
    } else {
      $this->view('movimientos/salida', compact('productos'));
    }
  }

  public function kardex() {
    $producto_id = intval($_GET['producto_id'] ?? 0);
    $productos = Producto::all();
    $movs = [];
    if ($producto_id) {
      $movs = Movimiento::kardex($producto_id);
    }
    $this->view('movimientos/kardex', compact('productos', 'movs', 'producto_id'));
  }
}
