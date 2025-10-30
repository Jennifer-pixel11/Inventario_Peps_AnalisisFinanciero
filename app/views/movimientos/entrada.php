<h3>Registrar entrada</h3>
<?php if (!empty($msg)): ?>
<div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<form method="post" class="card card-body">
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Producto</label>
      <select name="producto_id" class="form-select" required>
        <option value="">Seleccione...</option>
        <?php foreach ($productos as $p): ?>
          <option value="<?php echo $p['id']; ?>"><?php echo $p['nombre']; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">Fecha</label>
      <input type="date" name="fecha" class="form-control" required>
    </div>
    <div class="col-md-2">
      <label class="form-label">Cantidad</label>
      <input type="number" step="0.01" name="cantidad" class="form-control" required>
    </div>
    <div class="col-md-2">
      <label class="form-label">Costo unitario ($)</label>
      <input type="number" step="0.0001" name="costo" class="form-control" required>
    </div>
    <div class="col-md-12">
      <label class="form-label">Nota (opcional)</label>
      <input name="nota" class="form-control">
    </div>
  </div>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">Guardar entrada</button>
    <a class="btn btn-light" href="index.php?controller=productos&action=index">Volver</a>
  </div>
</form>
