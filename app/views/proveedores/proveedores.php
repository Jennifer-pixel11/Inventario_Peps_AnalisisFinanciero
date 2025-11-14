<div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0">Proveedores</h3>
  <a class="btn btn-primary" href="index.php?controller=proveedores&action=crear">Nuevo proveedor</a>
</div>
<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Empresa</th>
          <th>Nombre de Contacto</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th style="width:1%;"></th><!-- Columna de acciones -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($proveedores as $prov): ?>
          <tr>
            <td><?php echo htmlspecialchars($prov['nombre_empresa']); ?></td>
            <td><?php echo htmlspecialchars($prov['contacto_nombre']); ?></td>
            <td><?php echo htmlspecialchars($prov['telefono']); ?></td>
            <td><?php echo htmlspecialchars($prov['email']); ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-secondary" href="index.php?controller=proveedores&action=editar&id=<?php echo $prov['id']; ?>">Editar</a>
              
              <a class="btn btn-sm btn-outline-danger" 
                 href="index.php?controller=proveedores&action=eliminar&id=<?php echo $prov['id']; ?>" 
                 onclick="return confirm('¿Eliminar proveedor? Esto no se puede deshacer.')">
                 Eliminar
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>