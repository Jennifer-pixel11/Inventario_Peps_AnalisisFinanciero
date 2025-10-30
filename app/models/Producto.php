<?php
require_once __DIR__ . '/../core/Database.php';

class Producto {
  public static function all() {
    $pdo = Database::getInstance();
    return $pdo->query("SELECT * FROM productos ORDER BY id DESC")->fetchAll();
  }

  public static function find($id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
  }

  public static function create($codigo, $nombre, $unidad, $imagenPath = null) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("INSERT INTO productos (codigo, nombre, unidad, imagen) VALUES (?,?,?,?)");
    $stmt->execute([$codigo, $nombre, $unidad, $imagenPath]);
  }

  public static function update($id, $codigo, $nombre, $unidad, $imagenPath = null) {
    $pdo = Database::getInstance();
    if ($imagenPath) {
      $stmt = $pdo->prepare("UPDATE productos SET codigo=?, nombre=?, unidad=?, imagen=? WHERE id=?");
      $stmt->execute([$codigo, $nombre, $unidad, $imagenPath, $id]);
    } else {
      $stmt = $pdo->prepare("UPDATE productos SET codigo=?, nombre=?, unidad=? WHERE id=?");
      $stmt->execute([$codigo, $nombre, $unidad, $id]);
    }
  }

  public static function delete($id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("DELETE FROM productos WHERE id=?");
    $stmt->execute([$id]);
  }

  public static function actualizarStock($id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("
      SELECT 
        SUM(CASE WHEN tipo='ENTRADA' THEN cantidad ELSE 0 END) AS entr,
        SUM(CASE WHEN tipo='SALIDA'  THEN cantidad ELSE 0 END) AS sal
      FROM movimientos WHERE producto_id=?
    ");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    $stock = floatval($row['entr'] ?? 0) - floatval($row['sal'] ?? 0);
    $upd = $pdo->prepare("UPDATE productos SET stock=? WHERE id=?");
    $upd->execute([$stock, $id]);
  }

  /* Reporte: Productos con stock bajo */
  public static function lowStock($min = 5) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE stock <= ? ORDER BY stock ASC");
    $stmt->execute([$min]);
    return $stmt->fetchAll();
  }
}
