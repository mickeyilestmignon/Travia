<?php
global $cnx;
include '../include/connect.inc.php';
include '../class/ship.php';

// Truncate ships table and insert ships from json file

$cnx->exec("TRUNCATE TABLE ships");

if (isset($_POST['submit'])) {
    $ships = get_json_ships($_FILES['fileToUpload']['tmp_name']);
    $stmt = $cnx->prepare("INSERT INTO ships (id, name, camp, speed_kmh, capacity) VALUES (:id, :name, :camp, :speed_kmh, :capacity)");
    $count = 0;
    foreach ($ships as $ship) {

        $id = $ship->getId();
        $name = $ship->getName();
        $camp = $ship->getCamp();
        $speed_kmh = $ship->getSpeedKmh();
        $capacity = $ship->getCapacity();

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':camp', $camp, PDO::PARAM_STR);
        $stmt->bindParam(':speed_kmh', $speed_kmh);
        $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
        $stmt->execute();
        $count++;
    }
    header('Location: ../index.php?return_ships='.$count);
}
else {
    header('Location: ../index.php?return_ships=-1');
}