<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Movimiento.php';

class MovimientosController extends Controller {

  public function entrada() {
    $productos = Producto::all();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $producto_id = intval($_POST['producto_id']);
      $fecha       = $_POST['fecha'];
      $cantidad    = floatval($_POST['cantidad']);
      $costo       = floatval($_POST['costo']);
      $nota        = $_POST['nota'] ?? '';

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
      $fecha       = $_POST['fecha'];
      $cantidad    = floatval($_POST['cantidad']);
      $nota        = $_POST['nota'] ?? '';

      try {
        $info = Movimiento::registrarSalidaPEPS($producto_id, $fecha, $cantidad, $nota);
        $msg  = "Salida registrada. Costo promedio unitario: " . number_format($info['costo_unitario_promedio'], 4);
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
    $productos   = Producto::all();
    $movs        = [];
    $producto    = null;

    if ($producto_id) {
      // Buscar producto para mostrar en el encabezado del kardex
      foreach ($productos as $p) {
        if ((int)$p['id'] === $producto_id) {
          $producto = $p;
          break;
        }
      }
      // Movimientos del kardex
      $movs = Movimiento::kardex($producto_id);
    }

    $this->view('movimientos/kardex', compact('productos', 'movs', 'producto_id', 'producto'));
  }

  /**
   * Exportar Kardex a Excel con el mismo formato de la vista
   */
  public function kardexExcel() {
    $producto_id = intval($_GET['producto_id'] ?? 0);

    if (!$producto_id) {
      header("Location: index.php?controller=movimientos&action=kardex");
      exit;
    }

    // Productos y producto seleccionado
    $productos = Producto::all();
    $producto  = null;
    foreach ($productos as $p) {
      if ((int)$p['id'] === $producto_id) {
        $producto = $p;
        break;
      }
    }

    if (!$producto) {
      header("Location: index.php?controller=movimientos&action=kardex");
      exit;
    }

    // Movimientos del kardex (mismos que usas en la vista)
    $movs = Movimiento::kardex($producto_id);

    // Nombre del archivo
    $nombreArchivo = 'kardex_' . preg_replace('/\s+/', '_', ($producto['nombre'] ?? 'producto')) . '.xls';

    // Encabezados para Excel
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename={$nombreArchivo}");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<meta charset='UTF-8'>";

    ?>
    <table border="1" cellspacing="0" cellpadding="4">
      <!-- Encabezado tipo hoja -->
      <tr>
        <th colspan="11" style="background:#222;color:#fff;font-weight:bold;text-align:center;">
          GYM SPORT CENTER
        </th>
      </tr>
      <tr>
        <th colspan="11" style="background:#f0f0f0;font-weight:bold;text-align:center;">
          TARJETA KARDEX
        </th>
      </tr>
      <tr>
        <td colspan="11">
          <strong>PRODUCTO:</strong>
          <?= htmlspecialchars(($producto['codigo'] ?? '') . ' - ' . ($producto['nombre'] ?? '')) ?><br>
          <strong>UNIDAD:</strong> <?= htmlspecialchars($producto['unidad'] ?? '') ?><br>
          <strong>MÉTODO:</strong> Primeras Entradas Primeras Salidas (PEPS)<br>
          <strong>Fecha de generación:</strong> <?= date("d/m/Y H:i:s") ?>
        </td>
      </tr>

      <!-- ENCABEZADO DE TABLA tal como en la vista -->
      <tr style="background:#d9d9d9;font-weight:bold;text-align:center;">
        <th rowspan="2">FECHA</th>
        <th rowspan="2">CONCEPTO</th>
        <th colspan="3">ENTRADAS</th>
        <th colspan="3">SALIDAS</th>
        <th colspan="3">EXISTENCIAS</th>
      </tr>
      <tr style="background:#d9d9d9;font-weight:bold;text-align:center;">
        <th>U.</th><th>COSTO</th><th>TOTAL</th>
        <th>U.</th><th>COSTO</th><th>TOTAL</th>
        <th>U.</th><th>COSTO</th><th>TOTAL</th>
      </tr>

      <?php foreach ($movs as $row): ?>
        <?php
          $f = $row['fecha'] ?? '';
          $fecha      = $f ? date('d/m/Y', strtotime($f)) : '';
          $concepto   = $row['concepto']      ?? '';

          $entrada_u      = $row['entrada_u']      ?? 0;
          $entrada_costo  = $row['entrada_costo']  ?? 0;
          $entrada_total  = $row['entrada_total']  ?? 0;

          $salida_u       = $row['salida_u']       ?? 0;
          $salida_costo   = $row['salida_costo']   ?? 0;
          $salida_total   = $row['salida_total']   ?? 0;

          $exist_u        = $row['exist_u']        ?? 0;
          $exist_costo    = $row['exist_costo']    ?? 0;
          $exist_total    = $row['exist_total']    ?? 0;
        ?>
        <tr>
          <!-- FECHA -->
          <td><?= htmlspecialchars($fecha) ?></td>

          <!-- CONCEPTO -->
          <td><?= htmlspecialchars($concepto) ?></td>

          <!-- ENTRADAS -->
          <td style="text-align:right;">
            <?= $entrada_u != 0 ? number_format($entrada_u, 2) : '' ?>
          </td>
          <td style="text-align:right;">
            <?= $entrada_u != 0 ? '$ ' . number_format($entrada_costo, 2) : '' ?>
          </td>
          <td style="text-align:right;">
            <?= $entrada_u != 0 ? '$ ' . number_format($entrada_total, 2) : '' ?>
          </td>

          <!-- SALIDAS -->
          <td style="text-align:right;">
            <?= $salida_u != 0 ? number_format($salida_u, 2) : '' ?>
          </td>
          <td style="text-align:right;">
            <?= $salida_u != 0 ? '$ ' . number_format($salida_costo, 2) : '' ?>
          </td>
          <td style="text-align:right;">
            <?= $salida_u != 0 ? '$ ' . number_format($salida_total, 2) : '' ?>
          </td>

          <!-- EXISTENCIAS -->
          <td style="text-align:right;">
            <?= $exist_u != 0 ? number_format($exist_u, 2) : '' ?>
          </td>
          <td style="text-align:right;">
            <?= $exist_u != 0 ? '$ ' . number_format($exist_costo, 2) : '' ?>
          </td>
          <td style="text-align:right;">
            <?= $exist_u != 0 ? '$ ' . number_format($exist_total, 2) : '' ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
    <?php

    exit;
  }

}
