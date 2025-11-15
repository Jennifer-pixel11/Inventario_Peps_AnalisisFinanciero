<?php 
$config = include __DIR__ . '/../../config/config.php';
$base   = $config['app']['base_url']; 
?>

<div class="card">
  <div class="card-body">
    <h3 class="mb-3 text-white">Editar producto</h3>

    <?php if (!empty($msg)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="row g-3">

      <div class="col-md-4">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control"
               value="<?= htmlspecialchars($producto['codigo']); ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control"
               value="<?= htmlspecialchars($producto['nombre']); ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Unidad/Talla</label>
        <input type="text" name="unidad" class="form-control"
               value="<?= htmlspecialchars($producto['unidad']); ?>" required>
      </div>

      <div class="col-md-4">
        <label class="form-label">Stock</label>
        <input type="number" step="0.01" name="stock" class="form-control"
               value="<?= htmlspecialchars($producto['stock']); ?>">
      </div>

      <div class="col-md-4">
        <label class="form-label">Proveedor</label>
        <select name="proveedor_id" class="form-select">
          <option value="">Sin proveedor</option>
          <?php foreach ($proveedores as $prov): ?>
            <option value="<?= $prov['id']; ?>"
              <?= (isset($producto['proveedor_id']) && $producto['proveedor_id'] == $prov['id']) ? 'selected' : ''; ?>>
              <?= htmlspecialchars($prov['nombre_empresa']); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-4">
        <label class="form-label">Imagen del producto</label>
        <input type="file" name="imagen" class="form-control">
        <?php if (!empty($producto['imagen'])): ?>
          <div class="mt-2">
            <img src="<?= $base . '/' . htmlspecialchars($producto['imagen']); ?>"
                 alt="Imagen actual"
                 style="max-width:120px;max-height:120px;object-fit:cover;border-radius:10px;box-shadow:0 0 8px #00ff7f88;">
          </div>
        <?php endif; ?>
      </div>

      <div class="col-12">
        <button class="btn btn-success">Actualizar</button>
        <a href="index.php?controller=productos&action=index" class="btn btn-volver ms-2">Volver</a>
      </div>

    </form>
  </div>
</div>
