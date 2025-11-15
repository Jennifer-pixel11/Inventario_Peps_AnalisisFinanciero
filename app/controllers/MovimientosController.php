<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Movimiento.php';
require_once __DIR__ . '/../models/Lote.php';
require_once __DIR__ . '/../models/Proveedor.php';
require_once __DIR__ . '/../core/Database.php';
class MovimientosController extends Controller {

    // =====================
    // COMPRAS (ENTRADAS)
    // =====================
public function entrada() {
    $productos   = Producto::all();
    $proveedores = Proveedor::all();
    $doc_creado  = null;
    $msg         = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $producto_id    = intval($_POST['producto_id'] ?? 0);
        $proveedor_id   = !empty($_POST['proveedor_id']) ? intval($_POST['proveedor_id']) : null;
        $fecha          = trim($_POST['fecha'] ?? '');
        $cantidad       = isset($_POST['cantidad']) ? floatval($_POST['cantidad']) : 0;
        $costo          = isset($_POST['costo']) ? floatval($_POST['costo']) : 0;
        $num_doc_compra = trim($_POST['num_doc_compra'] ?? '');
        $nota           = trim($_POST['nota'] ?? '');

        // VALIDACIONES
        $errores = [];

        if ($producto_id <= 0) {
            $errores[] = "Debe seleccionar un producto.";
        }
        if ($fecha === '') {
            $errores[] = "La fecha de compra es obligatoria.";
        }
        if ($cantidad <= 0) {
            $errores[] = "La cantidad debe ser mayor que cero.";
        }
        if ($costo <= 0) {
            $errores[] = "El costo unitario debe ser mayor que cero.";
        }

        // (Opcional) recomendar número de factura
        if ($num_doc_compra === '') {
            // No lo hago obligatorio, solo advertencia suave
            // $errores[] = "Debe ingresar el número de factura.";
        }

        if (!empty($errores)) {
            // Devolvemos los mensajes unidos con <br>
            $msg = "<strong>Corrige los siguientes campos:</strong><br>" . implode("<br>", $errores);
            $this->view('movimientos/entrada', compact('productos', 'proveedores', 'msg', 'doc_creado'));
            return;
        }

        try {
            Movimiento::registrarEntrada(
                $producto_id,
                $fecha,
                $cantidad,
                $costo,
                $nota,
                $proveedor_id,
                $num_doc_compra
            );
            $msg        = "Compra registrada correctamente.";
            $doc_creado = $num_doc_compra;
        } catch (Exception $e) {
            $msg = "Error: " . $e->getMessage();
        }
    }

    $this->view('movimientos/entrada', compact('productos', 'proveedores', 'msg', 'doc_creado'));
}


    // =====================
    // VENTAS (SALIDAS)
    // =====================
public function salida() {
    $productos    = Producto::all();
    $doc_creado_v = null;
    $msg          = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $producto_id    = intval($_POST['producto_id'] ?? 0);
        $fecha          = trim($_POST['fecha'] ?? '');
        $cantidad       = isset($_POST['cantidad']) ? floatval($_POST['cantidad']) : 0;
        $precio_venta   = isset($_POST['precio_venta']) && $_POST['precio_venta'] !== ''
                          ? floatval($_POST['precio_venta'])
                          : null;
        $cliente_nombre = trim($_POST['cliente_nombre'] ?? '');
        $cliente_nit    = trim($_POST['cliente_nit'] ?? '');
        $num_doc_venta  = trim($_POST['num_doc_venta'] ?? '');
        $nota           = trim($_POST['nota'] ?? '');

        // VALIDACIONES
        $errores = [];

        if ($producto_id <= 0) {
            $errores[] = "Debe seleccionar un producto.";
        }
        if ($fecha === '') {
            $errores[] = "La fecha de venta es obligatoria.";
        }
        if ($cantidad <= 0) {
            $errores[] = "La cantidad vendida debe ser mayor que cero.";
        }
        if ($precio_venta !== null && $precio_venta <= 0) {
            $errores[] = "El precio de venta debe ser mayor que cero.";
        }

        if (!empty($errores)) {
            $msg = "<strong>Corrige los siguientes campos:</strong><br>" . implode("<br>", $errores);
            $this->view('movimientos/salida', compact('productos', 'msg', 'doc_creado_v'));
            return;
        }

        try {
            $info = Movimiento::registrarSalidaPEPS(
                $producto_id,
                $fecha,
                $cantidad,
                $nota,
                $precio_venta,
                $cliente_nombre ?: null,
                $cliente_nit ?: null,
                $num_doc_venta ?: null
            );

            $msg = "Venta registrada. Costo unitario promedio: " .
                   number_format($info['costo_unitario_promedio'], 4);

            if ($info['precio_venta'] !== null) {
                $msg .= " | Precio venta: " . number_format($info['precio_venta'], 2) .
                        " | Total venta: " . number_format($info['total_venta'], 2);
            }

            $doc_creado_v = $num_doc_venta;

        } catch (Exception $e) {
            $msg = "Error: " . $e->getMessage();
        }
    }

    $this->view('movimientos/salida', compact('productos', 'msg', 'doc_creado_v'));
}



    // =====================
    // KARDEX
    // =====================
    public function kardex() {

        $producto_id = intval($_GET['producto_id'] ?? 0);
        $productos   = Producto::all();

        $movs     = [];
        $producto = null;

        if ($producto_id) {
            $movs     = Movimiento::kardex($producto_id);
            $producto = Producto::find($producto_id);
        }

        $this->view('movimientos/kardex', compact('productos', 'movs', 'producto_id', 'producto'));
    }

    // =====================
    // EXPORTAR KARDEX
    // =====================
public function kardexExcel() {

    $producto_id = intval($_GET['producto_id'] ?? 0);

    if (!$producto_id) {
        die("Producto inválido.");
    }

    $producto = Producto::find($producto_id);
    $movs     = Movimiento::kardex($producto_id);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=kardex_producto_{$producto_id}.xls");

    include __DIR__ . '/../views/movimientos/kardex_excel.php';
}


        // ==========================
    // FACTURA DE COMPRA
    // ==========================
    public function facturaCompra() {
        $num_doc = $_GET['num_doc_compra'] ?? '';

        if ($num_doc === '') {
            die("Número de factura de compra no especificado.");
        }

        $pdo = Database::getInstance();

        // Traemos todos los movimientos que pertenecen a esa factura
        $stmt = $pdo->prepare("
            SELECT m.*,
                   p.nombre AS producto_nombre,
                   p.codigo AS producto_codigo,
                   pr.nombre_empresa AS proveedor_nombre,
                   pr.contacto_nombre,
                   pr.telefono,
                   pr.email,
                   pr.direccion
            FROM movimientos m
            JOIN productos p   ON p.id = m.producto_id
            LEFT JOIN proveedor pr ON pr.id = m.proveedor_id
            WHERE m.num_doc_compra = ?
              AND m.tipo = 'ENTRADA'
            ORDER BY m.id ASC
        ");
        $stmt->execute([$num_doc]);
        $items = $stmt->fetchAll();

        if (!$items) {
            die("No se encontraron registros para la factura de compra: " . htmlspecialchars($num_doc));
        }

        // Usamos la primera fila como encabezado de la factura
        $factura = $items[0];

        $this->view('movimientos/factura_compra', compact('items', 'factura'));
    }

    // ==========================
    // FACTURA DE VENTA
    // ==========================
    public function facturaVenta() {
        $num_doc = $_GET['num_doc_venta'] ?? '';

        if ($num_doc === '') {
            die("Número de factura de venta no especificado.");
        }

        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("
            SELECT m.*,
                   p.nombre AS producto_nombre,
                   p.codigo AS producto_codigo
            FROM movimientos m
            JOIN productos p ON p.id = m.producto_id
            WHERE m.num_doc_venta = ?
              AND m.tipo = 'SALIDA'
            ORDER BY m.id ASC
        ");
        $stmt->execute([$num_doc]);
        $items = $stmt->fetchAll();

        if (!$items) {
            die("No se encontraron registros para la factura de venta: " . htmlspecialchars($num_doc));
        }

        $factura = $items[0];

        $this->view('movimientos/factura_venta', compact('items', 'factura'));
    }

}
