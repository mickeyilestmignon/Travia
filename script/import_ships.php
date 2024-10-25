<?php
global $cnx;
include '../include/connect.inc.php';
include '../class/ship.php';

if (isset($_POST['submit'])) {
    $ships = get_json_ships($_FILES['fileToUpload']['tmp_name']);
    $stmt = $cnx->prepare("INSERT INTO ships (id, name, camp, speed_kmh, capacity) VALUES (:id, :name, :camp, :speed_kmh, :capacity)");
    $count = $stmt->rowCount();
    foreach ($ships as $ship) {
        $stmt->bindParam(':id', $ship->getId(), PDO::PARAM_INT);
        $stmt->bindParam(':name', $ship->getName(), PDO::PARAM_STR);
        $stmt->bindParam(':camp', $ship->getCamp(), PDO::PARAM_STR);
        $stmt->bindParam(':speed_kmh', $ship->getSpeedKmh());
        $stmt->bindParam(':capacity', $ship->getCapacity(), PDO::PARAM_STR);
        $stmt->execute();
    }
    header('Location: ../index.php?return='.$count);
}
else {
    header('Location: ../index.php?return=-1');
}