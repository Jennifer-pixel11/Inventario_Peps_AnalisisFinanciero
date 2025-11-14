<h3>Nuevo producto</h3>
<form method="post" enctype="multipart/form-data" class="card card-body">
  <div class="row g-3">
    <div class="col-md-3">
      <label class="form-label">Código</label>
      <input name="codigo" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Nombre</label>
      <input name="nombre" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Unidad</label>
      <input name="unidad" class="form-control" value="unidad">
    </div>
    <div class="col-md-6">
      <label class="form-label">Imagen (opcional)</label>
      <input type="file" name="imagen" accept="image/*" class="form-control">
    </div>
  </div>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">Guardar</button>
        <a href="index.php" class="btn-volver">Cancelar</a>
  </div>
</form>
