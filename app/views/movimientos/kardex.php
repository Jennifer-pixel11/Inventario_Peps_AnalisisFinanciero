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
          <option value="<?php echo $p['id']; ?>"
            <?php echo (($producto_id ?? 0) == $p['id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($p['codigo'] . ' - ' . $p['nombre']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-primary">Ver Kardex</button>
    </div>
  </div>
</form>

<?php if (!empty($movs)): ?>
<div class="card">
  <div class="card-body">

    <!-- Encabezado tipo hoja Excel -->
    <div class="text-center mb-3">
      <h5 class="mb-0">GYM SPORT CENTER</h5>
      <div>TARJETA KARDEX</div>
    </div>

    <div class="mb-3">
      <div><strong>PRODUCTO:</strong>
        <?php echo htmlspecialchars(($producto['codigo'] ?? '') . ' ' . ($producto['nombre'] ?? '')); ?>
      </div>
      <div><strong>UNIDAD:</strong>
        <?php echo htmlspecialchars($producto['unidad'] ?? ''); ?>
      </div>
      <div><strong>MÉTODO:</strong> Primeras Entradas Primeras Salidas (PEPS)</div>
    </div>

 <div class="d-flex justify-content-between align-items-center mb-3">
  <h3 class="m-0 text-white">Kardex PEPS</h3>

  <?php if (!empty($producto_id)): ?>
    <a href="index.php?controller=movimientos&action=kardexExcel&producto_id=<?= $producto_id ?>"
       class="btn btn-success">
      <i class="bi bi-file-earmark-excel"></i> Exportar Kardex a Excel
    </a>
  <?php endif; ?>
</div>



    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-success">
          <tr>
            <th rowspan="2">FECHA</th>
            <th rowspan="2">CONCEPTO</th>
            <th colspan="3">ENTRADAS</th>
            <th colspan="3">SALIDAS</th>
            <th colspan="3">EXISTENCIAS</th>
          </tr>
          <tr>
            <th>U.</th><th>COSTO</th><th>TOTAL</th>
            <th>U.</th><th>COSTO</th><th>TOTAL</th>
            <th>U.</th><th>COSTO</th><th>TOTAL</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($movs as $row): ?>
          <tr>
            <td>
              <?php
                $f = $row['fecha'] ?? '';
                echo $f ? date('d/m/Y', strtotime($f)) : '';
              ?>
            </td>
            <td class="text-start">
              <?php echo htmlspecialchars($row['concepto'] ?? ''); ?>
            </td>

            <!-- ENTRADAS -->
            <td class="text-end">
              <?php echo $row['entrada_u'] != 0 ? number_format($row['entrada_u'], 2) : ''; ?>
            </td>
            <td class="text-end">
              <?php echo $row['entrada_u'] != 0 ? '$ ' . number_format($row['entrada_costo'], 2) : ''; ?>
            </td>
            <td class="text-end">
              <?php echo $row['entrada_u'] != 0 ? '$ ' . number_format($row['entrada_total'], 2) : ''; ?>
            </td>

            <!-- SALIDAS -->
            <td class="text-end">
              <?php echo $row['salida_u'] != 0 ? number_format($row['salida_u'], 2) : ''; ?>
            </td>
            <td class="text-end">
              <?php echo $row['salida_u'] != 0 ? '$ ' . number_format($row['salida_costo'], 2) : ''; ?>
            </td>
            <td class="text-end">
              <?php echo $row['salida_u'] != 0 ? '$ ' . number_format($row['salida_total'], 2) : ''; ?>
            </td>

            <!-- EXISTENCIAS (saldo acumulado) -->
            <td class="text-end">
              <?php echo $row['exist_u'] != 0 ? number_format($row['exist_u'], 2) : ''; ?>
            </td>
            <td class="text-end">
              <?php echo $row['exist_u'] != 0 ? '$ ' . number_format($row['exist_costo'], 2) : ''; ?>
            </td>
            <td class="text-end">
              <?php echo $row['exist_u'] != 0 ? '$ ' . number_format($row['exist_total'], 2) : ''; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<?php elseif (isset($producto_id) && $producto_id): ?>
<div class="alert alert-warning">
  No hay movimientos para este producto.
</div>
<?php endif; ?>
