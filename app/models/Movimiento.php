<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Lote.php';

class Movimiento {
  public static function registrarEntrada($producto_id, $fecha, $cantidad, $costo_unitario, $nota='') {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();
    try {
      $lote_id = Lote::crearEntrada($producto_id, $fecha, $costo_unitario, $cantidad);
      $total = $cantidad * $costo_unitario;
      $stmt = $pdo->prepare("INSERT INTO movimientos (producto_id, tipo, fecha, cantidad, costo_unitario, total, lote_id, nota) VALUES (?,?,?,?,?,?,?,?)");
      $stmt->execute([$producto_id, 'ENTRADA', $fecha . ' 00:00:00', $cantidad, $costo_unitario, $total, $lote_id, $nota]);
      Producto::actualizarStock($producto_id);
      $pdo->commit();
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  public static function registrarSalidaPEPS($producto_id, $fecha, $cantidad_salida, $nota='') {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();
    try {
      $restante = $cantidad_salida;
      $costo_total_salida = 0.0;

      $lotes = Lote::lotesDisponiblesFIFO($producto_id);
      foreach ($lotes as $lote) {
        if ($restante <= 0) break;
        $toma = min($restante, $lote['cantidad_disponible']);
        if ($toma <= 0) continue;

        Lote::consumir($lote['id'], $toma);

        $costo = $toma * floatval($lote['costo_unitario']);
        $costo_total_salida += $costo;

        $stmt = $pdo->prepare("INSERT INTO movimientos (producto_id, tipo, fecha, cantidad, costo_unitario, total, lote_id, nota) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$producto_id, 'SALIDA', $fecha . ' 00:00:00', $toma, $lote['costo_unitario'], $costo, $lote['id'], $nota]);

        $restante -= $toma;
      }

      if ($restante > 0) {
        throw new Exception('Stock insuficiente para realizar la salida solicitada.');
      }

      $costo_promedio_salida = $costo_total_salida / $cantidad_salida;

      Producto::actualizarStock($producto_id);
      $pdo->commit();

      return ['costo_unitario_promedio' => $costo_promedio_salida, 'costo_total' => $costo_total_salida];
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  public static function kardex($producto_id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM movimientos WHERE producto_id=? ORDER BY fecha ASC, id ASC");
    $stmt->execute([$producto_id]);
    return $stmt->fetchAll();
  }

  /* Reporte: Movimientos por rango de fechas (opcionalmente por producto) */
  public static function movimientosPorRango($desde, $hasta, $producto_id = null) {
    $pdo = Database::getInstance();
    if ($producto_id) {
      $stmt = $pdo->prepare("SELECT * FROM movimientos WHERE fecha BETWEEN ? AND ? AND producto_id=? ORDER BY fecha ASC, id ASC");
      $stmt->execute([$desde . ' 00:00:00', $hasta . ' 23:59:59', $producto_id]);
    } else {
      $stmt = $pdo->prepare("SELECT * FROM movimientos WHERE fecha BETWEEN ? AND ? ORDER BY fecha ASC, id ASC");
      $stmt->execute([$desde . ' 00:00:00', $hasta . ' 23:59:59']);
    }
    return $stmt->fetchAll();
  }

  /* Reporte: Valorización actual de inventario (PEPS) a partir de lotes restantes */
  public static function valorizacionActual() {
    $pdo = Database::getInstance();
    $sql = "
      SELECT p.id AS producto_id, p.codigo, p.nombre, p.unidad, p.stock,
             SUM(l.cantidad_disponible * l.costo_unitario) AS valor_actual,
             SUM(l.cantidad_disponible) AS cant_disponible
      FROM productos p
      LEFT JOIN lotes l ON l.producto_id = p.id
      GROUP BY p.id, p.codigo, p.nombre, p.unidad, p.stock
      ORDER BY p.nombre ASC
    ";
    return $pdo->query($sql)->fetchAll();
  }
}
