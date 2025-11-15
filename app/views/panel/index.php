<div
  class="p-4 mb-4 rounded"
  style="background: linear-gradient(135deg, #1e1f21 0%, #000000 100%); box-shadow: 0 3px 15px rgba(0,0,0,0.6);"
>
  <div class="d-flex flex-column justify-content-center align-items-center text-center">

    <img src="public/img/LOGO.jpg" alt="Logo Gym"
         style="
            width: 220px;
            height: 220px;
            object-fit: contain;
            margin-bottom: 15px;
            filter: drop-shadow(0 0 12px #00ff7f) drop-shadow(0 0 20px #00ff7f88);
         ">
    
    <h2 class="m-0"
        style="
          color: #00ff7f;
          text-shadow: 
            0 0 10px #00ff7f,
            0 0 20px #00ff7f88,
            0 0 30px #00ff7f55;
          letter-spacing: 2px;
          font-weight: bold;
        ">
      GYM SPORT CENTER
    </h2>

  </div>
</div>



<?php if (!empty($bajoStock)): ?>
<div class="alert alert-warning d-flex align-items-center gap-2">
  <span class="badge bg-danger rounded-pill" style="color:#fff !important;">
    <?php echo $totalBajoStock; ?>
  </span>
  <div style="color:#000 !important; font-weight:600;">
    Hay productos en <strong style="color:#000 !important;">bajo stock</strong>. 
    Revisa las compras para evitar quedarte sin suplementos o accesorios.
  </div>
  <a class="btn btn-sm ms-auto"
     style="border:1px solid #000 !important; color:#000 !important; background:#ffc107 !important;"
     href="index.php?controller=reportes&action=bajo_stock">
    Ver detalle
  </a>
</div>
<?php endif; ?>


<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Productos registrados</div>
        <div class="h3 mb-0"><?php echo $totalProductos; ?></div>
        <div class="text-muted small">Suplementos, accesorios...</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Productos en bajo stock</div>
        <div class="h3 mb-0 text-danger"><?php echo $totalBajoStock; ?></div>
        <div class="text-muted small">Reponer cuanto antes.</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Unidades totales en inventario</div>
        <div class="h3 mb-0"><?php echo number_format($stockTotal,2); ?></div>
        <div class="text-muted small">Sumatoria de existencias.</div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Valor aproximado del inventario</div>
        <div class="h4 mb-0">$ <?php echo number_format($valorInventario,2); ?></div>
        <div class="text-muted small">Según lotes PEPS.</div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-dark text-white">
        Productos en bajo stock
      </div>
      <div class="card-body table-responsive">
        <?php if (empty($bajoStock)): ?>
          <div class="text-muted small">No hay productos bajo el umbral configurado.</div>
        <?php else: ?>
        <table class="table table-sm align-middle mb-0">
          <thead>
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Unidad</th>
              <th class="text-end">Stock</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bajoStock as $p): ?>
            <tr>
              <td><?php echo htmlspecialchars($p['codigo']); ?></td>
              <td><?php echo htmlspecialchars($p['nombre']); ?></td>
              <td><?php echo htmlspecialchars($p['unidad']); ?></td>
              <td class="text-end">
                <span class="badge bg-danger">
                  <?php echo number_format($p['stock'],2); ?>
                </span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-dark text-white">
        Últimos movimientos (compras y ventas)
      </div>
      <div class="card-body table-responsive">
        <?php if (empty($ultimos)): ?>
          <div class="text-muted small">Aún no se han registrado movimientos.</div>
        <?php else: ?>
        <table class="table table-sm align-middle mb-0">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Tipo</th>
              <th class="text-end">Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($ultimos as $m): ?>
            <tr>
              <td><?php echo date('d/m/Y', strtotime($m['fecha'])); ?></td>
              <td><?php echo htmlspecialchars($m['producto_nombre']); ?></td>
              <td>
                <?php if ($m['tipo'] === 'ENTRADA'): ?>
                  <span class="badge bg-success">Compra</span>
                <?php else: ?>
                  <span class="badge bg-warning text-dark">Venta</span>
                <?php endif; ?>
              </td>
              <td class="text-end"><?php echo number_format($m['cantidad'],2); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<div class="row g-4 mt-4">
  <div class="col-12">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-dark text-white">
        Resumen mensual: Ventas vs Costo de ventas (<?= htmlspecialchars($anio ?? date('Y')); ?>)

      </div>
      <div class="card-body">
        <canvas id="chartVentasCostos" height="120"></canvas>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('chartVentasCostos').getContext('2d');

  const labels = <?= json_encode($labels ?? []); ?>;
  const ventas = <?= json_encode($ventas ?? []); ?>;
  const costos = <?= json_encode($costos ?? []); ?>;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        { label: 'Ventas', data: ventas, borderWidth: 1 },
        { label: 'Costo de ventas', data: costos, borderWidth: 1 }
      ]
    },
    options: {
      responsive: true
    }
  });
</script>



