<?php
if (isset($_GET['depart']) && isset($_GET['arrivee'])) {
    global $cnx;
    include("../include/connect.inc.php");

    $query = $cnx->query("SELECT id FROM planets WHERE name = '".$_GET['depart']."'");
    $depart = $query->fetch()->id;
    $query = $cnx->query("SELECT id FROM planets WHERE name = '".$_GET['arrivee']."'");
    $arrivee = $query->fetch()->id;
    $date = date("Y-m-d h:i:sa");

    $stmt = $cnx->prepare("INSERT INTO logs (id_planete_depart, id_planete_arrivee, time) VALUES (:id_planete_depart, :id_planete_arrivee, :time)");
    $stmt->bindParam(':id_planete_depart', $depart, PDO::PARAM_INT);
    $stmt->bindParam(':id_planete_arrivee', $arrivee, PDO::PARAM_INT);
    $stmt->bindParam(':time', $date);
    $stmt->execute();

    header("Location: ../search_results.php?depart=$depart&arrivee=$arrivee");
}
else {
    header("Location: ../index.php");
}