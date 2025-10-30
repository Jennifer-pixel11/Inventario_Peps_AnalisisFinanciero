<h3 class="mb-3">Valorización actual (PEPS)</h3>
<div class="d-flex justify-content-end mb-2">
  <form method="post" action="index.php?controller=reportes&action=valorizacion_xls">
    <button class="btn btn-success btn-sm">Exportar a Excel</button>
  </form>
</div>

<div class="card shadow-sm">
  <div class="card-body table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Código</th><th>Producto</th><th>Unidad</th><th>Stock</th><th>Cant. en lotes</th><th>Valor actual ($)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($resumen as $r): ?>
          <tr>
            <td><?php echo htmlspecialchars($r['codigo']); ?></td>
            <td><?php echo htmlspecialchars($r['nombre']); ?></td>
            <td><?php echo htmlspecialchars($r['unidad']); ?></td>
            <td><?php echo number_format($r['stock'],2); ?></td>
            <td><?php echo number_format($r['cant_disponible'] ?? 0,2); ?></td>
            <td><strong><?php echo number_format($r['valor_actual'] ?? 0,4); ?></strong></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
