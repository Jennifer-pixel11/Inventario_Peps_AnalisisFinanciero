<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductosController extends Controller {
  private function guardarImagenSiExiste() {
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) return null;

    $tmp = $_FILES['imagen']['tmp_name'];
    $type = mime_content_type($tmp);
    if (!in_array($type, ['image/jpeg','image/png','image/webp','image/gif'])) {
      return null; // podrías lanzar excepción si prefieres validar estricto
    }
    $ext = match ($type) {
      'image/jpeg' => 'jpg',
      'image/png'  => 'png',
      'image/webp' => 'webp',
      'image/gif'  => 'gif',
      default => 'jpg'
    };
    $filename = uniqid('img_', true) . '.' . $ext;
    $dest = __DIR__ . '/../../public/uploads/' . $filename;
    if (!is_dir(__DIR__ . '/../../public/uploads')) {
      mkdir(__DIR__ . '/../../public/uploads', 0775, true);
    }
    move_uploaded_file($tmp, $dest);

    $config = include __DIR__ . '/../config/config.php';
    $base = rtrim($config['app']['base_url'] ?? '', '/');
    return $base . '/public/uploads/' . $filename;
  }

  public function index() {
    $productos = Producto::all();
    $this->view('productos/index', compact('productos'));
  }

  public function crear() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $codigo = $_POST['codigo'] ?? '';
      $nombre = $_POST['nombre'] ?? '';
      $unidad = $_POST['unidad'] ?? 'unidad';
      $img = $this->guardarImagenSiExiste();
      Producto::create($codigo, $nombre, $unidad, $img);
      $this->redirect('index.php?controller=productos&action=index');
    } else {
      $this->view('productos/crear');
    }
  }

  public function editar() {
    $id = intval($_GET['id'] ?? 0);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $codigo = $_POST['codigo'] ?? '';
      $nombre = $_POST['nombre'] ?? '';
      $unidad = $_POST['unidad'] ?? 'unidad';
      $img = $this->guardarImagenSiExiste(); // puede ser null si no suben nueva
      Producto::update($id, $codigo, $nombre, $unidad, $img);
      $this->redirect('index.php?controller=productos&action=index');
    } else {
      $producto = Producto::find($id);
      $this->view('productos/editar', compact('producto'));
    }
  }

  public function eliminar() {
    $id = intval($_GET['id'] ?? 0);
    Producto::delete($id);
    $this->redirect('index.php?controller=productos&action=index');
  }
}
