<?php
require_once __DIR__ . '/../core/Database.php';

class Producto
{
    // Nombre real de la tabla
    private static $tabla = 'productos';

    // ============================
    // LISTADO Y BÚSQUEDA
    // ============================

    // Listar todos los productos (con nombre de proveedor si existe)
    public static function all() {
        $pdo = Database::getInstance();
        $sql = "SELECT p.*, pr.nombre_empresa AS proveedor_nombre
                FROM " . self::$tabla . " p
                LEFT JOIN proveedor pr ON pr.id = p.proveedor_id
                ORDER BY p.id DESC";
        return $pdo->query($sql)->fetchAll();
    }

    // Buscar un producto por id
    public static function find($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM " . self::$tabla . " WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // ============================
    // CREAR / ACTUALIZAR / BORRAR
    // ============================

    // Crear producto (incluye imagen y proveedor)
    public static function create($codigo, $nombre, $unidad, $stock, $imagen_ruta = null, $proveedor_id = null) {
        $pdo = Database::getInstance();
        $sql = "INSERT INTO " . self::$tabla . " (codigo, nombre, unidad, stock, imagen, proveedor_id)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $codigo,
            $nombre,
            $unidad,
            $stock,
            $imagen_ruta,
            $proveedor_id
        ]);
    }

    // Actualizar producto
    public static function update($id, $codigo, $nombre, $unidad, $stock, $imagen_ruta = null, $proveedor_id = null) {
        $pdo = Database::getInstance();

        $sql = "UPDATE " . self::$tabla . "
                SET codigo = ?, nombre = ?, unidad = ?, stock = ?, imagen = ?, proveedor_id = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $codigo,
            $nombre,
            $unidad,
            $stock,
            $imagen_ruta,
            $proveedor_id,
            $id
        ]);
    }

    // Eliminar producto
    public static function delete($id) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("DELETE FROM " . self::$tabla . " WHERE id = ?");
        $stmt->execute([$id]);
    }

    // ============================
    // STOCK (USADO POR PEPS)
    // ============================

    /**
     * Recalcula el stock del producto a partir de la tabla lotes (PEPS).
     * Se suma cantidad_disponible de todos los lotes del producto
     * y se actualiza la columna stock en la tabla productos.
     */
    public static function actualizarStock($id_producto) {
        $pdo = Database::getInstance();

        // calcular stock real desde lotes (PEPS)
        $stmt = $pdo->prepare("
            SELECT COALESCE(SUM(cantidad_disponible), 0) AS stock_real
            FROM lotes
            WHERE producto_id = ?
        ");
        $stmt->execute([$id_producto]);
        $row = $stmt->fetch();

        $stock_real = $row ? floatval($row['stock_real']) : 0;

        // actualizar columna stock en productos
        $stmt2 = $pdo->prepare("
            UPDATE " . self::$tabla . "
            SET stock = ?
            WHERE id = ?
        ");
        $stmt2->execute([$stock_real, $id_producto]);
    }

        // ============================
    // PRODUCTOS EN BAJO STOCK
    // ============================

    /**
     * Devuelve los productos con stock por debajo o igual a un umbral.
     * Por defecto, umbral = 5 unidades.
     * Se usa para el panel (alertas de bajo stock).
     */
    public static function lowStock($umbral = 5) {
        $pdo = Database::getInstance();

        // Si en tu BD tienes una columna 'stock_minimo', podrías usar:
        // SELECT * FROM productos WHERE stock <= stock_minimo
        // Aquí dejo una versión simple con umbral fijo.
        $stmt = $pdo->prepare("
            SELECT p.*, pr.nombre_empresa AS proveedor_nombre
            FROM " . self::$tabla . " p
            LEFT JOIN proveedor pr ON pr.id = p.proveedor_id
            WHERE p.stock <= ?
            ORDER BY p.stock ASC, p.nombre ASC
        ");
        $stmt->execute([ $umbral ]);
        return $stmt->fetchAll();
    }

}
