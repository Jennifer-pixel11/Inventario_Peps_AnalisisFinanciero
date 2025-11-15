<h3 class="mb-3 text-white">RESUMEN FINANCIERO</h3>

<form method="get" class="card card-body mb-4">
  <input type="hidden" name="controller" value="reportes">
  <input type="hidden" name="action" value="resumen_financiero">

  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label class="form-label">Desde</label>
      <input type="date" name="desde" class="form-control" value="<?= htmlspecialchars($desde); ?>" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Hasta</label>
      <input type="date" name="hasta" class="form-control" value="<?= htmlspecialchars($hasta); ?>" required>
    </div>
    <div class="col-md-4">
      <button class="btn btn-success mt-3 mt-md-0">Aplicar filtro</button>
    </div>
  </div>
</form>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Compras en el período</div>
        <div class="h4 mb-0">$ <?= number_format($datos['total_compras'], 2); ?></div>
        <div class="text-muted small">Entradas valoradas por costo.</div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Ventas en el período</div>
        <div class="h4 mb-0">$ <?= number_format($datos['total_ventas'], 2); ?></div>
        <div class="text-muted small">Según precios de venta registrados.</div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Costo de ventas</div>
        <div class="h4 mb-0">$ <?= number_format($datos['costo_ventas'], 2); ?></div>
        <div class="text-muted small">Costo según PEPS.</div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <?php
      $util = $datos['utilidad_bruta'];
      $claseUtil = $util >= 0 ? 'text-success' : 'text-danger';
    ?>
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="text-muted small mb-1">Utilidad bruta</div>
        <div class="h4 mb-0 <?= $claseUtil; ?>">
          $ <?= number_format($util, 2); ?>
        </div>
        <div class="text-muted small">Ventas - costo de ventas.</div>
      </div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <div class="text-muted small mb-1">Valor actual del inventario (PEPS)</div>
    <div class="h4 mb-0">$ <?= number_format($datos['valor_inventario'], 2); ?></div>
    <div class="text-muted small">Según lotes y entradas vigentes.</div>
  </div>
</div>
