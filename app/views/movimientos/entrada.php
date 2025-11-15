<h3 class="mb-3 text-white">Registrar compra (Entrada)</h3>

<?php if (!empty($msg)): ?>
  <div class="alert alert-info"><?= $msg; ?></div>
<?php endif; ?>

<?php if (!empty($doc_creado)): ?>
  <div class="mb-3">
    <a href="index.php?controller=movimientos&action=facturaCompra&num_doc_compra=<?= urlencode($doc_creado); ?>"
       class="btn btn-success">
      Ver factura de compra <?= htmlspecialchars($doc_creado); ?>
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
        <label class="form-label">Proveedor</label>
        <select name="proveedor_id" class="form-select">
          <option value="">Seleccione...</option>
          <?php foreach ($proveedores as $prov): ?>
            <option value="<?= $prov['id']; ?>">
              <?= htmlspecialchars($prov['nombre_empresa']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Fecha de compra</label>
        <input type="date" name="fecha" class="form-control"
               value="<?= date('Y-m-d'); ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Número de factura / documento</label>
        <input type="text" name="num_doc_compra" class="form-control"
               placeholder="Ej: FC-00123">
      </div>

      <div class="col-md-4">
        <label class="form-label">Cantidad (unidades)</label>
        <input type="number" step="0.01" min="0.01"
               name="cantidad" class="form-control" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Costo unitario ($)</label>
        <input type="number" step="0.0001" min="0.0001"
               name="costo" class="form-control" required>
      </div>

      <div class="col-12">
        <label class="form-label">Nota / Concepto</label>
        <textarea name="nota" class="form-control" rows="2"
                  placeholder="Ej: Compra de suplementos, lote promoción..."></textarea>
      </div>

      <div class="col-12">
        <button class="btn btn-success">Registrar compra</button>
        <a href="index.php" class="btn btn-volver ms-2">Volver</a>
      </div>

    </form>
  </div>
</div>
