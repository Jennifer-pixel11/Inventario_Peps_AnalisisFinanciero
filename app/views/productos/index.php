<?php 
$config = include __DIR__ . '/../../config/config.php';
$base   = $config['app']['base_url']; 
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0 text-white">Productos</h3>
  <a class="btn btn-primary" href="index.php?controller=productos&action=crear">Nuevo producto</a>
</div>

<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped align-middle text-center">
      <thead>
        <tr>
          <th>Imagen</th>
          <th>Código</th>
          <th>Nombre</th>
          <th>Unidad/Talla</th>
          <th>Proveedor</th>
          <th>Stock</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($productos as $p): ?>
        <tr>
          <td style="width:80px;">
            <?php if (!empty($p['imagen'])): ?>
              <img src="<?php echo $base . '/' . htmlspecialchars($p['imagen']); ?>"
                   style="height:56px;width:56px;object-fit:cover;border-radius:10px;box-shadow:0 0 8px #00ff7f88;">
            <?php else: ?>
              <div class="text-muted">—</div>
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars($p['codigo']); ?></td>
          <td><?php echo htmlspecialchars($p['nombre']); ?></td>
          <td><?php echo htmlspecialchars($p['unidad']); ?></td>
          <td>
            <?php echo !empty($p['proveedor_nombre'])
              ? htmlspecialchars($p['proveedor_nombre'])
              : '<span class="text-muted">Sin proveedor</span>'; ?>
          </td>
          <td><?php echo number_format($p['stock'],2); ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-secondary"
               href="index.php?controller=productos&action=editar&id=<?php echo $p['id']; ?>">Editar</a>
            <a class="btn btn-sm btn-outline-danger"
               href="index.php?controller=productos&action=eliminar&id=<?php echo $p['id']; ?>"
               onclick="return confirm('¿Eliminar producto?')">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
