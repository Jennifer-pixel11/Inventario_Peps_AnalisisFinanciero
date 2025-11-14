<?php
require_once __DIR__ . '/../core/Database.php';

class Proveedor {

    // 1. Listar todos los proveedores (Para la tabla principal y los select)
    public static function all() {
        $pdo = Database::getInstance();
        // Ordenados por ID descendente para ver los nuevos primero
        return $pdo->query("SELECT * FROM proveedor ORDER BY id DESC")->fetchAll();
    }

    // 2. Buscar un solo proveedor (Para rellenar el formulario de editar)
    public static function find($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM proveedor WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // 3. Crear nuevo proveedor
    public static function create($nombre, $contacto, $telefono, $email, $direccion) {
        $pdo = Database::getInstance();
        $sql = "INSERT INTO proveedor (nombre_empresa, contacto_nombre, telefono, email, direccion) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $contacto, $telefono, $email, $direccion]);
    }

    // 4. Actualizar proveedor existente
    public static function update($id, $nombre, $contacto, $telefono, $email, $direccion) {
        $pdo = Database::getInstance();
        $sql = "UPDATE proveedor 
                SET nombre_empresa=?, contacto_nombre=?, telefono=?, email=?, direccion=? 
                WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre, $contacto, $telefono, $email, $direccion, $id]);
    }

    // 5. Eliminar (Con protección anti-errores)
    public static function delete($id) {
        $pdo = Database::getInstance();
        try {
            $stmt = $pdo->prepare("DELETE FROM proveedor WHERE id=?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Si intentas borrar un proveedor que tiene productos, MySQL dará error.
            // Aquí atrapamos ese error para que no "explote" la página.
            // Puedes redirigir o mostrar mensaje si prefieres.
            // Error code 23000 suele ser integridad referencial (Foreign Key)
            if ($e->getCode() == '23000') {
                echo "<script>alert('No se puede eliminar: Este proveedor tiene productos asignados.'); window.location.href='index.php?controller=proveedores&action=index';</script>";
                exit;
            }
            throw $e;
        }
    }
}