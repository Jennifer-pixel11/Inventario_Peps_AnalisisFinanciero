<?php 
$config = include __DIR__ . '/../../config/config.php';
$base   = $config['app']['base_url']; 
?>

<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Factura de venta <?= htmlspecialchars($factura['num_doc_venta']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #000;
      color: #fff;
    }
    .factura-box {
      background: #050d0a;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 0 15px #00ff7f55;
    }
    .neon-title {
      color: #00ff7f;
      text-shadow: 0 0 8px #00ff7f;
    }
    .table-dark td, .table-dark th {
      background-color: #ffffffff !important;
    }
    .btn-print {
      box-shadow: 0 0 8px #00ff7f88;
    }
    /* TOTAL en negro y con fondo claro para que se distinga */
    .total-negro {
      color: #000 !important;
      font-weight: 700 !important;
      background-color: #f8f9fa !important; /* gris claro tipo Bootstrap */
    }
  </style>
</head>
<body class="p-4">

<div class="container factura-box">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="neon-title mb-0">GYM SPORT CENTER</h2>
      <div>Inventario PEPS · Ventas</div>
    </div>
    <div class="text-end">
      <div><strong>Factura de Venta:</strong> <?= htmlspecialchars($factura['num_doc_venta']); ?></div>
      <div><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($factura['fecha'])); ?></div>
    </div>
  </div>

  <hr class="border border-success border-1 opacity-50">

  <div class="row mb-3">
    <div class="col-md-6">
      <h5 class="text-success">Cliente</h5>
      <div><strong><?= htmlspecialchars($factura['cliente_nombre'] ?? 'Consumidor final'); ?></strong></div>
      <?php if (!empty($factura['cliente_nit'])): ?>
        <div>NIT: <?= htmlspecialchars($factura['cliente_nit']); ?></div>
      <?php endif; ?>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
      <h5 class="text-success">Gimnasio</h5>
      <div><strong>GYM SPORT CENTER</strong></div>
      <div>Control de Inventario PEPS</div>
    </div>
  </div>

  <div class="table-responsive mb-3">
    <table class="table table-dark table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>Código</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio U.</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
      <?php 
        $gran_total = 0; // 👈 AQUÍ se define el total

        foreach ($items as $it):
          $precio_venta = ($it['precio_venta'] !== null) ? floatval($it['precio_venta']) : 0;
          $subtotal     = $precio_venta * floatval($it['cantidad']);
          $gran_total  += $subtotal;
      ?>
        <tr>
          <td><?= htmlspecialchars($it['producto_codigo']); ?></td>
          <td><?= htmlspecialchars($it['producto_nombre']); ?></td>
          <td><?= number_format($it['cantidad'], 2); ?></td>
          <td>$ <?= number_format($precio_venta, 4); ?></td>
          <td>$ <?= number_format($subtotal, 2); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-end total-negro">Total venta</th>
          <th class="total-negro">$ <?= number_format($gran_total, 2); ?></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <?php if (!empty($factura['nota'])): ?>
    <div class="mb-3">
      <strong>Nota:</strong> <?= nl2br(htmlspecialchars($factura['nota'])); ?>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mt-4">
    <a href="javascript:window.print()" class="btn btn-success btn-print">
      Imprimir / Guardar como PDF
    </a>
    <a href="index.php?controller=movimientos&action=salida" class="btn btn-volver">
      Volver a ventas
    </a>
  </div>
</div>

</body>
</html>
