<?php
require_once __DIR__ . '/../core/Database.php';

class Lote {
  public static function crearEntrada($producto_id, $fecha, $costo_unitario, $cantidad) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("INSERT INTO lotes (producto_id, fecha, costo_unitario, cantidad_inicial, cantidad_disponible) VALUES (?,?,?,?,?)");
    $stmt->execute([$producto_id, $fecha, $costo_unitario, $cantidad, $cantidad]);
    return $pdo->lastInsertId();
  }

  public static function lotesDisponiblesFIFO($producto_id) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM lotes WHERE producto_id=? AND cantidad_disponible>0 ORDER BY fecha ASC, id ASC");
    $stmt->execute([$producto_id]);
    return $stmt->fetchAll();
  }

  public static function consumir($lote_id, $cantidad) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("UPDATE lotes SET cantidad_disponible = cantidad_disponible - ? WHERE id=? AND cantidad_disponible >= ?");
    $stmt->execute([$cantidad, $lote_id, $cantidad]);
  }
}
