<?php $config = include __DIR__ . '/../../config/config.php'; $base = $config['app']['base_url']; ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventario PEPS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $base; ?>/public/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark brand-gradient border-0 mb-4 shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="index.php">
      <span class="brand-dot"></span> Inventario PEPS 
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php?controller=productos&action=index">Productos</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=movimientos&action=entrada">Entradas</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=movimientos&action=salida">Salidas</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=movimientos&action=kardex">Kardex</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Reportes</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?controller=reportes&action=index">Panel</a></li>
            <li><a class="dropdown-item" href="index.php?controller=reportes&action=movimientos">Movimientos por fecha</a></li>
            <li><a class="dropdown-item" href="index.php?controller=reportes&action=valorizacion">Valorización actual</a></li>
            <li><a class="dropdown-item" href="index.php?controller=reportes&action=bajo_stock">Bajo stock</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mb-5">
