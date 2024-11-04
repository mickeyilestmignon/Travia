<?php
// A script like import_ships.phph but for planets, to be inserted in the planets table in the database.

global $cnx;
include '../include/connect.inc.php';
include '../class/planet.php';

// Truncate planets table and insert planets from json file

$cnx->exec("TRUNCATE TABLE planets");

if (isset($_POST['submit'])) {
    $planets = get_json_planets($_FILES['fileToUpload']['tmp_name']);
    $stmt = $cnx->prepare("INSERT INTO planets (id, name, image, coord, X, Y, SubGridCoord, SubGridX, SubGridY, region, sector, suns, moons, position, distance, LengthDay, LengthYear, diameter, gravity, trips) VALUES (:id, :name, :image, :coord, :X, :Y, :SubGridCoord, :SubGridX, :SubGridY, :region, :sector, :suns, :moons, :position, :distance, :LengthDay, :LengthYear, :diameter, :gravity, :trips)");
    $count = 0;
    foreach ($planets as $planet) {

        $id = $planet->getId();
        $name = $planet->getName();
        $image = $planet->getImage();
        $coord = $planet->getCoord();
        $X = $planet->getX();
        $Y = $planet->getY();
        $SubGridCoord = $planet->getSubGridCoord();
        $SubGridX = $planet->getSubGridX();
        $SubGridY = $planet->getSubGridY();
        $region = $planet->getRegion();
        $sector = $planet->getSector();
        $suns = $planet->getSuns();
        $moons = $planet->getMoons();
        $position = $planet->getPosition();
        $distance = $planet->getDistance();
        $LengthDay = $planet->getLengthDay();
        $LengthYear = $planet->getLengthYear();
        $diameter = $planet->getDiameter();
        $gravity = $planet->getGravity();
        //$trips = $planet->getTrips();
        $trips = 'TEMP';

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':image', $image, PDO::PARAM_STR);
        $stmt->bindParam(':coord', $coord, PDO::PARAM_STR);
        $stmt->bindParam(':X', $X, PDO::PARAM_INT);
        $stmt->bindParam(':Y', $Y, PDO::PARAM_INT);
        $stmt->bindParam(':SubGridCoord', $SubGridCoord, PDO::PARAM_STR);
        $stmt->bindParam(':SubGridX', $SubGridX);
        $stmt->bindParam(':SubGridY', $SubGridY);
        $stmt->bindParam(':region', $region, PDO::PARAM_STR);
        $stmt->bindParam(':sector', $sector, PDO::PARAM_STR);
        $stmt->bindParam(':suns', $suns, PDO::PARAM_INT);
        $stmt->bindParam(':moons', $moons, PDO::PARAM_INT);
        $stmt->bindParam(':position', $position, PDO::PARAM_INT);
        $stmt->bindParam(':distance', $distance, PDO::PARAM_INT);
        $stmt->bindParam(':LengthDay', $LengthDay, PDO::PARAM_INT);
        $stmt->bindParam(':LengthYear', $LengthYear, PDO::PARAM_INT);
        $stmt->bindParam(':diameter', $diameter, PDO::PARAM_INT);
        $stmt->bindParam(':gravity', $gravity, PDO::PARAM_INT);
        $stmt->bindParam(':trips', $trips, PDO::PARAM_STR);
        $stmt->execute();
        $count++;
    }
    header('Location: ../index.php?return_planets='.$count);
}
else {
    header('Location: ../index.php?return_planets=-1');
}
