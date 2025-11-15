<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/Producto.php';
require_once __DIR__ . '/Lote.php';

class Movimiento {

  /* =====================================
   *  ENTRADAS (COMPRAS)
   * ===================================== */
  // Nota: añadimos proveedor y num_doc_compra al final
  public static function registrarEntrada(
      $producto_id,
      $fecha,
      $cantidad,
      $costo_unitario,
      $nota = '',
      $proveedor_id = null,
      $num_doc_compra = null
  ) {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();
    try {
      // crear lote (PEPS)
      $lote_id = Lote::crearEntrada($producto_id, $fecha, $costo_unitario, $cantidad);

      $total = $cantidad * $costo_unitario;

      // ahora insertamos también proveedor y num_doc_compra
      $stmt = $pdo->prepare("
        INSERT INTO movimientos (
          producto_id, tipo, fecha, cantidad, costo_unitario, total,
          lote_id, nota,
          proveedor_id, num_doc_compra,
          cliente_nombre, cliente_nit,
          num_doc_venta, precio_venta, total_venta
        )
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
      ");
      $stmt->execute([
        $producto_id,
        'ENTRADA',
        $fecha . ' 00:00:00',
        $cantidad,
        $costo_unitario,
        $total,
        $lote_id,
        $nota,
        $proveedor_id,
        $num_doc_compra,
        null,   // cliente_nombre
        null,   // cliente_nit
        null,   // num_doc_venta
        null,   // precio_venta
        null    // total_venta
      ]);

      // recalcular stock desde lotes
      Producto::actualizarStock($producto_id);
      $pdo->commit();
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  /* =====================================
   *  SALIDAS (VENTAS) - PEPS
   * ===================================== */
  // Nota: añadimos precio_venta y datos de cliente
  public static function registrarSalidaPEPS(
      $producto_id,
      $fecha,
      $cantidad_salida,
      $nota = '',
      $precio_venta = null,
      $cliente_nombre = null,
      $cliente_nit = null,
      $num_doc_venta = null
  ) {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();
    try {
      $restante          = $cantidad_salida;
      $costo_total_salida = 0.0;

      // aseguramos que precio_venta sea float o null
      $precio_venta = ($precio_venta !== null && $precio_venta !== '')
                      ? floatval($precio_venta)
                      : null;

      // ingresos por venta (si se proporcionó precio)
      $total_venta = ($precio_venta !== null)
                     ? $precio_venta * $cantidad_salida
                     : null;

      // lotes disponibles en orden FIFO
      $lotes = Lote::lotesDisponiblesFIFO($producto_id);
      foreach ($lotes as $lote) {
        if ($restante <= 0) break;

        $disponible = floatval($lote['cantidad_disponible']);
        $toma       = min($restante, $disponible);
        if ($toma <= 0) continue;

        // consumir del lote (controla que no quede negativo)
        Lote::consumir($lote['id'], $toma);

        $costo = $toma * floatval($lote['costo_unitario']);
        $costo_total_salida += $costo;

        // se registra UN movimiento por cada lote afectado
        $stmt = $pdo->prepare("
          INSERT INTO movimientos (
            producto_id, tipo, fecha, cantidad, costo_unitario, total,
            lote_id, nota,
            proveedor_id, num_doc_compra,
            cliente_nombre, cliente_nit,
            num_doc_venta, precio_venta, total_venta
          )
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
          $producto_id,
          'SALIDA',
          $fecha . ' 00:00:00',
          $toma,
          $lote['costo_unitario'],
          $costo,          // total (costo de venta)
          $lote['id'],
          $nota,
          null,            // proveedor_id (no aplica en ventas)
          null,            // num_doc_compra
          $cliente_nombre,
          $cliente_nit,
          $num_doc_venta,
          $precio_venta,
          $total_venta     // mismo total_venta en cada fila; se puede mejorar pero basta para proyecto
        ]);

        $restante -= $toma;
      }

      if ($restante > 0) {
        throw new Exception('Stock insuficiente para realizar la salida solicitada.');
      }

      if ($cantidad_salida <= 0) {
        throw new Exception('Cantidad de salida inválida.');
      }

      // datos de costo de venta (totales, redondeados)
      $costo_promedio_salida = round($costo_total_salida / $cantidad_salida, 6);
      $costo_total_salida    = round($costo_total_salida, 6);

      Producto::actualizarStock($producto_id);
      $pdo->commit();

      return [
        'costo_unitario_promedio' => $costo_promedio_salida,
        'costo_total'             => $costo_total_salida,
        'precio_venta'            => $precio_venta,
        'total_venta'             => $total_venta
      ];
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }

  /* ===================
   *  KARDEX DETALLADO
   * =================== */
  public static function kardex($producto_id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("
      SELECT *
      FROM movimientos
      WHERE producto_id = ?
      ORDER BY fecha ASC, id ASC
    ");
    $stmt->execute([$producto_id]);
    $movs = $stmt->fetchAll();

    $saldo_unidades = 0.0;
    $saldo_valor    = 0.0;

    $kardex = [];

    foreach ($movs as $m) {
      $cantidad       = floatval($m['cantidad']);
      $costo_unitario = floatval($m['costo_unitario']);
      $total          = floatval($m['total']);

      $entrada_u = 0.0; $entrada_c = 0.0; $entrada_t = 0.0;
      $salida_u  = 0.0; $salida_c  = 0.0; $salida_t  = 0.0;

      if ($m['tipo'] === 'ENTRADA') {
        // ENTRADA
        $entrada_u = $cantidad;
        $entrada_c = $costo_unitario;
        $entrada_t = $total;

        $saldo_unidades += $cantidad;
        $saldo_valor    += $total;
      } else {
        // SALIDA (ya viene calculada con PEPS por lote)
        $salida_u = $cantidad;
        $salida_c = $costo_unitario;
        $salida_t = $total;

        $saldo_unidades -= $cantidad;
        $saldo_valor    -= $total;
      }

      if ($saldo_unidades < 0) $saldo_unidades = 0;
      if ($saldo_valor < 0)    $saldo_valor    = 0;

      $exist_u     = $saldo_unidades;
      $exist_total = $saldo_valor;
      $exist_costo = $exist_u > 0 ? ($exist_total / $exist_u) : 0.0;

      $kardex[] = [
        'fecha'          => $m['fecha'],
        'concepto'       => ($m['nota'] !== '' && $m['nota'] !== null)
                            ? $m['nota']
                            : ($m['tipo'] === 'ENTRADA' ? 'Entrada' : 'Salida'),
        'entrada_u'      => $entrada_u,
        'entrada_costo'  => $entrada_c,
        'entrada_total'  => $entrada_t,
        'salida_u'       => $salida_u,
        'salida_costo'   => $salida_c,
        'salida_total'   => $salida_t,
        'exist_u'        => $exist_u,
        'exist_costo'    => $exist_costo,
        'exist_total'    => $exist_total,
      ];
    }

    return $kardex;
  }

  /* =======================================
   *  REPORTES: MOVIMIENTOS Y VALORIZACIÓN
   * ======================================= */

  public static function movimientosPorRango($desde, $hasta, $producto_id = null) {
    $pdo = Database::getInstance();
    $desde_dt = $desde . ' 00:00:00';
    $hasta_dt = $hasta . ' 23:59:59';

    if ($producto_id) {
      $stmt = $pdo->prepare("
        SELECT *
        FROM movimientos
        WHERE fecha BETWEEN ? AND ?
          AND producto_id = ?
        ORDER BY fecha ASC, id ASC
      ");
      $stmt->execute([$desde_dt, $hasta_dt, $producto_id]);
    } else {
      $stmt = $pdo->prepare("
        SELECT *
        FROM movimientos
        WHERE fecha BETWEEN ? AND ?
        ORDER BY fecha ASC, id ASC
      ");
      $stmt->execute([$desde_dt, $hasta_dt]);
    }
    return $stmt->fetchAll();
  }

  
  public static function valorizacionActual() {
    $pdo = Database::getInstance();
    $sql = "
      SELECT
        p.id     AS producto_id,
        p.codigo,
        p.nombre,
        p.unidad,
        p.stock,
        SUM(l.cantidad_disponible * l.costo_unitario) AS valor_actual,
        SUM(l.cantidad_disponible)                    AS cant_disponible
      FROM productos p
      LEFT JOIN lotes l ON l.producto_id = p.id
      GROUP BY
        p.id, p.codigo, p.nombre, p.unidad, p.stock
      ORDER BY p.nombre ASC
    ";
    return $pdo->query($sql)->fetchAll();
  }

public static function resumenFinanciero($desde, $hasta) {
    $pdo = Database::getInstance();

    $desde_dt = $desde . ' 00:00:00';
    $hasta_dt = $hasta . ' 23:59:59';

    $stmt = $pdo->prepare("
        SELECT
          SUM(CASE WHEN tipo = 'ENTRADA' THEN total              ELSE 0 END) AS total_compras,
          SUM(CASE WHEN tipo = 'SALIDA'  THEN COALESCE(total_venta,0) ELSE 0 END) AS total_ventas,
          SUM(CASE WHEN tipo = 'SALIDA'  THEN total              ELSE 0 END) AS costo_ventas
        FROM movimientos
        WHERE fecha BETWEEN ? AND ?
    ");
    $stmt->execute([$desde_dt, $hasta_dt]);
    $row = $stmt->fetch();

    $total_compras  = floatval($row['total_compras'] ?? 0);
    $total_ventas   = floatval($row['total_ventas']  ?? 0);
    $costo_ventas   = floatval($row['costo_ventas']  ?? 0);
    $utilidad_bruta = $total_ventas - $costo_ventas;

    $valorizacion = self::valorizacionActual();
    $valor_inventario = 0;
    foreach ($valorizacion as $v) {
        $valor_inventario += floatval($v['valor_actual'] ?? 0);
    }

    return [
        'total_compras'    => $total_compras,
        'total_ventas'     => $total_ventas,
        'costo_ventas'     => $costo_ventas,
        'utilidad_bruta'   => $utilidad_bruta,
        'valor_inventario' => $valor_inventario,
    ];
}


  /* ======================
   *  ÚLTIMOS MOVIMIENTOS
   * ====================== */
  public static function ultimosMovimientos($limite = 10) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("
      SELECT m.*, p.nombre AS producto_nombre
      FROM movimientos m
      JOIN productos p ON p.id = m.producto_id
      ORDER BY m.fecha DESC, m.id DESC
      LIMIT ?
    ");
    $stmt->bindValue(1, (int)$limite, \PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
