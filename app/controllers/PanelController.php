<?php
require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Movimiento.php';

class PanelController extends Controller {

public function index() {
    // ==========================
    //  Datos actuales del panel
    // ==========================

    // Todos los productos
    $productos = Producto::all();
    $totalProductos = count($productos);

    // Productos en bajo stock
    // (Si quieres cambiar el umbral, puedes pasar un número: lowStock(5), etc.)
    $bajoStock      = Producto::lowStock();
    $totalBajoStock = count($bajoStock);

    // Stock total (sumatoria de stock de todos los productos)
    $stockTotal = 0;
    foreach ($productos as $p) {
        $stockTotal += floatval($p['stock']);
    }

    // Valor aproximado del inventario (a partir de la valorización PEPS)
    $valorInventario = 0;
    $valorizacion = Movimiento::valorizacionActual();
    foreach ($valorizacion as $item) {
        $valorInventario += floatval($item['valor_actual'] ?? 0);
    }

    // Últimos movimientos (compras y ventas)
    $ultimos = Movimiento::ultimosMovimientos(10);

    // ==========================
    //  Datos para el gráfico
    // ==========================

    // Año actual
    $anio  = date('Y');

    // Serie por mes: ventas y costos
    // (Asegúrate que en Movimiento.php exista el método ventasYCostosPorMes($anio))
    $serie = Movimiento::ventasYCostosPorMes($anio);

    // Preparamos arrays para pasarlos al JS (Chart.js)
    $labels = [];
    $ventas = [];
    $costos = [];

    foreach ($serie as $mes => $datos) {
        // $mes viene como "01", "02", ..., "12"
        $labels[] = $mes;
        $ventas[] = $datos['ventas'];
        $costos[] = $datos['costo_ventas'];
    }

    // ==========================
    //  Enviamos todo a la vista
    // ==========================

    $this->view(
        'panel/index',
        compact(
            'totalProductos',
            'bajoStock',
            'totalBajoStock',
            'stockTotal',
            'valorInventario',
            'ultimos',
            'anio',
            'labels',
            'ventas',
            'costos'
        )
    );
}



}
