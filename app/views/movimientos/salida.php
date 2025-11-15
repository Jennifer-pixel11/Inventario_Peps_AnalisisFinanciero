<h3 class="mb-3 text-white">Registrar venta (Salida)</h3>

<?php if (!empty($msg)): ?>
  <div class="alert alert-info"><?= $msg; ?></div>
<?php endif; ?>

<?php if (!empty($doc_creado_v)): ?>
  <div class="mb-3">
    <a href="index.php?controller=movimientos&action=facturaVenta&num_doc_venta=<?= urlencode($doc_creado_v); ?>"
       class="btn btn-success">
      Ver factura de venta <?= htmlspecialchars($doc_creado_v); ?>
    </a>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <form method="post" class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Producto</label>
        <select name="producto_id" class="form-select" required>
          <option value="">Seleccione...</option>
          <?php foreach ($productos as $p): ?>
            <option value="<?= $p['id']; ?>">
              <?= htmlspecialchars($p['codigo'] . ' - ' . $p['nombre']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Fecha de venta</label>
        <input type="date" name="fecha" class="form-control"
               value="<?= date('Y-m-d'); ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Cantidad vendida</label>
        <input type="number" step="0.01" min="0.01"
               name="cantidad" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Precio de venta unitario ($)</label>
        <input type="number" step="0.0001" min="0.0001"
               name="precio_venta" class="form-control"
               placeholder="Ej: 19.99">
      </div>

      <div class="col-md-4">
        <label class="form-label">Número de factura</label>
        <input type="text" name="num_doc_venta" class="form-control"
               placeholder="Ej: FV-00045">
      </div>

      <div class="col-md-4">
        <label class="form-label">NIT del cliente (opcional)</label>
        <input type="text" name="cliente_nit" class="form-control"
               placeholder="Ej: 0614-XXXXXX-001-1">
      </div>

      <div class="col-md-6">
        <label class="form-label">Nombre del cliente</label>
        <input type="text" name="cliente_nombre" class="form-control"
               placeholder="Nombre del cliente o socio">
      </div>

      <div class="col-12">
        <label class="form-label">Nota / Concepto</label>
        <textarea name="nota" class="form-control" rows="2"
                  placeholder="Ej: Venta de membresía, accesorios, suplementos..."></textarea>
      </div>

      <div class="col-12">
        <button class="btn btn-success">Registrar venta</button>
        <a href="index.php" class="btn btn-volver ms-2">Volver</a>
      </div>

    </form>
  </div>
</div>
