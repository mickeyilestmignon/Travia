<?php
global $cnx;
include "../include/connect.inc.php";

$searchTerm = $_GET['term']; // Termes de recherche reçus via AJAX

// Préparer la requête SQL
$sql = "SELECT name FROM planets WHERE name LIKE '%" . $searchTerm . "%'";
$result = $cnx->query($sql);

$data = [];
if ($result->rowCount() > 0) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row['name'];
    }
}

echo json_encode($data); // Retourner les résultats sous forme de JSON