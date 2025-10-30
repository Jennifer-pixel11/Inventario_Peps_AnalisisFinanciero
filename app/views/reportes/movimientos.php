<h3 class="mb-3">Reporte de movimientos</h3>
<form method="post" class="card card-body shadow-sm mb-3 stylish-card">
  <div class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label">Desde</label>
      <input type="date" name="desde" class="form-control" required value="<?php echo htmlspecialchars($filtros['desde'] ?? ''); ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Hasta</label>
      <input type="date" name="hasta" class="form-control" required value="<?php echo htmlspecialchars($filtros['hasta'] ?? ''); ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">Producto (opcional)</label>
      <select name="producto_id" class="form-select">
        <option value="">Todos</option>
        <?php foreach ($productos as $p): ?>
          <option value="<?php echo $p['id']; ?>" <?php echo (($filtros['producto_id'] ?? '') == $p['id']) ? 'selected' : ''; ?>>
            <?php echo $p['nombre']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2 d-flex gap-2">
      <button class="btn btn-primary w-100">Consultar</button>
    </div>
  </div>
</form>

<?php if (!empty($data)): ?>
<div class="d-flex justify-content-end mb-2">
  <form method="post" action="index.php?controller=reportes&action=movimientos_xls">
    <input type="hidden" name="desde" value="<?php echo htmlspecialchars($filtros['desde']); ?>">
    <input type="hidden" name="hasta" value="<?php echo htmlspecialchars($filtros['hasta']); ?>">
    <input type="hidden" name="producto_id" value="<?php echo htmlspecialchars($filtros['producto_id']); ?>">
    <button class="btn btn-success btn-sm">Exportar a Excel</button>
  </form>
</div>

<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Fecha</th><th>ID Prod.</th><th>Tipo</th><th>Cantidad</th><th>Costo unit.</th><th>Total</th><th>Lote</th><th>Nota</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $m): ?>
          <tr>
            <td><?php echo htmlspecialchars($m['fecha']); ?></td>
            <td><span class="badge text-bg-secondary"><?php echo $m['producto_id']; ?></span></td>
            <td>
              <?php if ($m['tipo']==='ENTRADA'): ?>
                <span class="badge text-bg-primary">ENTRADA</span>
              <?php else: ?>
                <span class="badge text-bg-warning">SALIDA</span>
              <?php endif; ?>
            </td>
            <td><?php echo number_format($m['cantidad'],2); ?></td>
            <td><?php echo number_format($m['costo_unitario'],4); ?></td>
            <td><?php echo number_format($m['total'],4); ?></td>
            <td><?php echo $m['lote_id'] ?? '-'; ?></td>
            <td><?php echo htmlspecialchars($m['nota'] ?? ''); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php endif; ?>
