<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Proveedor.php';

class ProductosController extends Controller
{
    public function index() {
        $productos = Producto::all();
        $this->view('productos/index', compact('productos'));
    }

    // ==========================
    // CREAR PRODUCTO
    // ==========================
    public function crear() {
        $proveedores = Proveedor::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigo'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $unidad = $_POST['unidad'] ?? '';
            $stock  = floatval($_POST['stock'] ?? 0);

            // Subir imagen (opcional)
            $imagen_ruta = null;
            if (!empty($_FILES['imagen']['name'])) {
                $carpetaDestino = 'public/img/productos/';
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name']);
                $rutaFinal     = $carpetaDestino . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
                    $imagen_ruta = $rutaFinal;
                }
            }

            // Proveedor
            $proveedor_id = !empty($_POST['proveedor_id']) ? intval($_POST['proveedor_id']) : null;

            // Crear producto
            Producto::create($codigo, $nombre, $unidad, $stock, $imagen_ruta, $proveedor_id);

            $msg = "Producto creado correctamente.";
            $this->view('productos/crear', compact('proveedores', 'msg'));
        } else {
            $this->view('productos/crear', compact('proveedores'));
        }
    }

    // ==========================
    // EDITAR PRODUCTO
    // ==========================
    public function editar() {
        $id = intval($_GET['id'] ?? 0);

        $producto    = Producto::find($id);
        $proveedores = Proveedor::all();

        if (!$producto) {
            header("Location: index.php?controller=productos&action=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = $_POST['codigo'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $unidad = $_POST['unidad'] ?? '';
            $stock  = floatval($_POST['stock'] ?? 0);

            $proveedor_id = !empty($_POST['proveedor_id']) ? intval($_POST['proveedor_id']) : null;

            // Mantener imagen actual si no sube nueva
            $imagen_ruta = $producto['imagen'] ?? null;

            if (!empty($_FILES['imagen']['name'])) {
                $carpetaDestino = 'public/img/productos/';
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                $nombreArchivo = time() . '_' . basename($_FILES['imagen']['name']);
                $rutaFinal     = $carpetaDestino . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
                    $imagen_ruta = $rutaFinal;
                }
            }

            Producto::update($id, $codigo, $nombre, $unidad, $stock, $imagen_ruta, $proveedor_id);

            $producto = Producto::find($id);
            $msg = "Producto actualizado correctamente.";
            $this->view('productos/editar', compact('producto', 'proveedores', 'msg'));
        } else {
            $this->view('productos/editar', compact('producto', 'proveedores'));
        }
    }

    // ==========================
    // ELIMINAR PRODUCTO
    // ==========================
    public function eliminar() {
        $id = intval($_GET['id'] ?? 0);
        if ($id) {
            Producto::delete($id);
        }
        header("Location: index.php?controller=productos&action=index");
        exit;
    }
}
