<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Movimiento.php';

class ReportesController extends Controller {

  public function index() {
    $this->view('reportes/index');
  }

  public function movimientos() {
    $productos = Producto::all();
    $data = [];
    $filtros = ['desde'=>'', 'hasta'=>'', 'producto_id'=>''];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $filtros['desde'] = $_POST['desde'] ?? '';
      $filtros['hasta'] = $_POST['hasta'] ?? '';
      $filtros['producto_id'] = $_POST['producto_id'] ?? '';
      $pid = !empty($filtros['producto_id']) ? intval($filtros['producto_id']) : null;
      $data = Movimiento::movimientosPorRango($filtros['desde'], $filtros['hasta'], $pid);
    }
    $this->view('reportes/movimientos', compact('productos', 'data', 'filtros'));
  }

  public function valorizacion() {
    $resumen = Movimiento::valorizacionActual();
    $this->view('reportes/valorizacion', compact('resumen'));
  }

  public function bajo_stock() {
    $min = isset($_GET['min']) ? intval($_GET['min']) : 5;
    $items = Producto::lowStock($min);
    $this->view('reportes/bajo_stock', compact('items', 'min'));
  }

  /* =======================
   *  EXPORTACIONES A EXCEL
   * ======================= */

  private function sendXls($filename, $html) {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "<html><head><meta charset='utf-8'></head><body>{$html}</body></html>";
    exit;
  }

  // Movimientos XLS
  public function movimientos_xls() {
    $desde = $_POST['desde'] ?? '';
    $hasta = $_POST['hasta'] ?? '';
    $producto_id = !empty($_POST['producto_id']) ? intval($_POST['producto_id']) : null;
    $data = Movimiento::movimientosPorRango($desde, $hasta, $producto_id);

    $rows = "";
    foreach ($data as $m) {
      $rows .= "<tr>"
        . "<td>".htmlspecialchars($m['fecha'])."</td>"
        . "<td>".htmlspecialchars($m['producto_id'])."</td>"
        . "<td>".htmlspecialchars($m['tipo'])."</td>"
        . "<td>".number_format($m['cantidad'],2,'.','')."</td>"
        . "<td>".number_format($m['costo_unitario'],4,'.','')."</td>"
        . "<td>".number_format($m['total'],4,'.','')."</td>"
        . "<td>".htmlspecialchars($m['lote_id'] ?? '')."</td>"
        . "<td>".htmlspecialchars($m['nota'] ?? '')."</td>"
        . "</tr>";
    }

    $html = "
      <h3>Movimientos {$desde} a {$hasta}</h3>
      <table border='1' cellspacing='0' cellpadding='4'>
        <thead>
          <tr>
            <th>Fecha</th><th>ID Producto</th><th>Tipo</th><th>Cantidad</th>
            <th>Costo unit.</th><th>Total</th><th>Lote</th><th>Nota</th>
          </tr>
        </thead>
        <tbody>{$rows}</tbody>
      </table>";
    $this->sendXls("movimientos_{$desde}_{$hasta}.xls", $html);
  }

  // Valorización actual XLS
  public function valorizacion_xls() {
    $resumen = Movimiento::valorizacionActual();
    $rows = "";
    foreach ($resumen as $r) {
      $rows .= "<tr>"
        . "<td>".htmlspecialchars($r['codigo'])."</td>"
        . "<td>".htmlspecialchars($r['nombre'])."</td>"
        . "<td>".htmlspecialchars($r['unidad'])."</td>"
        . "<td>".number_format($r['stock'],2,'.','')."</td>"
        . "<td>".number_format($r['cant_disponible'] ?? 0,2,'.','')."</td>"
        . "<td>".number_format($r['valor_actual'] ?? 0,4,'.','')."</td>"
        . "</tr>";
    }
    $html = "
      <h3>Valorización Actual (PEPS)</h3>
      <table border='1' cellspacing='0' cellpadding='4'>
        <thead>
          <tr>
            <th>Código</th><th>Producto</th><th>Unidad</th><th>Stock</th>
            <th>Cant. en lotes</th><th>Valor actual ($)</th>
          </tr>
        </thead>
        <tbody>{$rows}</tbody>
      </table>";
    $this->sendXls("valorizacion_actual.xls", $html);
  }

  // Bajo stock XLS
  public function bajo_stock_xls() {
    $min = isset($_POST['min']) ? intval($_POST['min']) : 5;
    $items = Producto::lowStock($min);
    $rows = "";
    foreach ($items as $p) {
      $rows .= "<tr>"
        . "<td>".htmlspecialchars($p['codigo'])."</td>"
        . "<td>".htmlspecialchars($p['nombre'])."</td>"
        . "<td>".htmlspecialchars($p['unidad'])."</td>"
        . "<td>".number_format($p['stock'],2,'.','')."</td>"
        . "</tr>";
    }
    $html = "
      <h3>Productos con Stock &le; {$min}</h3>
      <table border='1' cellspacing='0' cellpadding='4'>
        <thead><tr><th>Código</th><th>Producto</th><th>Unidad</th><th>Stock</th></tr></thead>
        <tbody>{$rows}</tbody>
      </table>";
    $this->sendXls("bajo_stock_{$min}.xls", $html);
  }
}
