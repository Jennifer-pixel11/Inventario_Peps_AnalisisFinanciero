<h3>Kardex por producto</h3>
<form method="get" class="card card-body mb-3">
  <input type="hidden" name="controller" value="movimientos">
  <input type="hidden" name="action" value="kardex">
  <div class="row g-3 align-items-end">
    <div class="col-md-6">
      <label class="form-label">Producto</label>
      <select name="producto_id" class="form-select">
        <option value="">Seleccione...</option>
        <?php foreach ($productos as $p): ?>
          <option value="<?php echo $p['id']; ?>" <?php echo (($producto_id ?? 0) == $p['id']) ? 'selected' : ''; ?>>
            <?php echo $p['nombre']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary">Ver Kardex</button>
    </div>
  </div>
</form>

<?php if (!empty($movs)): ?>
<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-sm table-striped align-middle">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Tipo</th>
          <th>Cantidad</th>
          <th>Costo unit.</th>
          <th>Total</th>
          <th>Lote</th>
          <th>Nota</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($movs as $m): ?>
          <tr>
            <td><?php echo htmlspecialchars($m['fecha']); ?></td>
            <td><?php echo $m['tipo']; ?></td>
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
<?php elseif(isset($producto_id) && $producto_id): ?>
<div class="alert alert-warning">No hay movimientos para este producto.</div>
<?php endif; ?>
