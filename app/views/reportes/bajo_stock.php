<h3 class="mb-3">Productos con bajo stock</h3>
<form method="get" class="card card-body shadow-sm mb-3 stylish-card">
  <input type="hidden" name="controller" value="reportes">
  <input type="hidden" name="action" value="bajo_stock">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label">Mínimo (<=)</label>
      <input type="number" name="min" class="form-control" value="<?php echo intval($min); ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100">Filtrar</button>
    </div>
  </div>
</form>

<div class="d-flex justify-content-end mb-2">
  <form method="post" action="index.php?controller=reportes&action=bajo_stock_xls">
    <input type="hidden" name="min" value="<?php echo intval($min); ?>">
    <button class="btn btn-success btn-sm">Exportar a Excel</button>
  </form>
</div>

<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light"><tr><th>Código</th><th>Producto</th><th>Unidad</th><th>Stock</th></tr></thead>
      <tbody>
        <?php foreach ($items as $p): ?>
          <tr>
            <td><?php echo htmlspecialchars($p['codigo']); ?></td>
            <td><?php echo htmlspecialchars($p['nombre']); ?></td>
            <td><?php echo htmlspecialchars($p['unidad']); ?></td>
            <td><?php echo number_format($p['stock'],2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
