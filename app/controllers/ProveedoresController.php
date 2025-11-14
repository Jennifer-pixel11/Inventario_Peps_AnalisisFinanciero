<?php
require_once __DIR__ . '/../core/Controller.php';
// OJO: Asegúrate de tener el modelo Proveedor.php creado como vimos antes
require_once __DIR__ . '/../models/Proveedor.php'; 

class ProveedoresController extends Controller {

  // Listar todos los proveedores
  public function index() {
    $proveedores = Proveedor::all();
    $this->view('proveedores/proveedores', compact('proveedores'));
  }

  // Formulario y Acción de Crear
  public function crear() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nombre   = $_POST['nombre_empresa'] ?? '';
      $contacto = $_POST['contacto_nombre'] ?? '';
      $telefono = $_POST['telefono'] ?? '';
      $email    = $_POST['email'] ?? '';
      $direccion= $_POST['direccion'] ?? '';

      // Llamada al modelo (necesitarás agregar el método create en Proveedor.php)
      Proveedor::create($nombre, $contacto, $telefono, $email, $direccion);
      
      $this->redirect('index.php?controller=proveedores&action=index');
    } else {
      $this->view('proveedores/crear');
    }
  }

  // Formulario y Acción de Editar
  public function editar() {
    $id = intval($_GET['id'] ?? 0);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $nombre   = $_POST['nombre_empresa'] ?? '';
      $contacto = $_POST['contacto_nombre'] ?? '';
      $telefono = $_POST['telefono'] ?? '';
      $email    = $_POST['email'] ?? '';
      $direccion= $_POST['direccion'] ?? '';

      Proveedor::update($id, $nombre, $contacto, $telefono, $email, $direccion);
      
      $this->redirect('index.php?controller=proveedores&action=index');
    } else {
      $proveedor = Proveedor::find($id);
      $this->view('proveedores/editar', compact('proveedor'));
    }
  }

  // Eliminar
  public function eliminar() {
    $id = intval($_GET['id'] ?? 0);
    try {
        Proveedor::delete($id);
    } catch (Exception $e) {
        // Aquí podrías manejar el error si intentan borrar un proveedor que tiene productos
        // die("No puedes borrar este proveedor porque tiene productos asignados");
    }
    $this->redirect('index.php?controller=proveedores&action=index');
  }
}