<?php
require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Movimiento.php';

class PanelController extends Controller {

  public function index() {
    // Productos
    $productos = Producto::all();
    $totalProductos = count($productos);

    $bajoStock = Producto::lowStock(5); // umbral 5 unidades
    $totalBajoStock = count($bajoStock);

    $stockTotal = 0;
    foreach ($productos as $p) {
      $stockTotal += floatval($p['stock']);
    }

    // Valorización actual
    $val = Movimiento::valorizacionActual();
    $valorInventario = 0;
    foreach ($val as $r) {
      $valorInventario += floatval($r['valor_actual']);
    }

    // Últimos movimientos (necesita método ultimosMovimientos en Movimiento.php)
    $ultimos = Movimiento::ultimosMovimientos(5);

    $this->view('panel/index', compact(
      'totalProductos',
      'totalBajoStock',
      'stockTotal',
      'valorInventario',
      'bajoStock',
      'ultimos'
    ));
  }
}
