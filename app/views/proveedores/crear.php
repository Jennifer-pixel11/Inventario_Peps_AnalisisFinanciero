<h3>Nuevo proveedor</h3>

<form method="post" class="card card-body">
  <div class="row g-3">

    <div class="col-md-6">
      <label class="form-label">Nombre de la empresa</label>
      <input name="nombre_empresa" class="form-control" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Nombre del contacto</label>
      <input name="contacto_nombre" class="form-control" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Teléfono</label>
      <input name="telefono" class="form-control">
    </div>

    <div class="col-md-8">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control">
    </div>

    <div class="col-12">
      <label class="form-label">Dirección</label>
      <textarea name="direccion" class="form-control" rows="2"></textarea>
    </div>

  </div>

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">Guardar</button>

    <a class="btn btn-light" 
       href="index.php?controller=proveedores&action=index">
      Cancelar
    </a>
  </div>
</form>
