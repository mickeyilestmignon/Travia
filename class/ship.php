<?php

include_once '/mnt/traban_home/3binf1/achirecesei/WWW/TraviaProject/include/connect.inc.php';

class ship {
    private int $id;
    private string $name;
    private string $camp;
    private float $speed_kmh;
    private int $capacity;

    function __construct($id, $name, $camp, $speed_kmh, $capacity) {
        $this->id = $id;
        $this->name = $name;
        $this->camp = $camp;
        $this->speed_kmh = $speed_kmh;
        $this->capacity = $capacity;
    }

    public function getId(): int
    { return $this->id; }
    public function getName(): string
    { return $this->name; }
    public function getCamp(): string
    { return $this->camp; }
    public function getSpeedKmh(): float
    { return $this->speed_kmh; }
    public function getCapacity(): int
    { return $this->capacity;}
}

function get_json_ships($json): array
{
    $json = file_get_contents($json);
    $data = json_decode($json, true);
    $ships = array();
    foreach ($data as $ship) {
        $ships[] = new ship($ship['id'], $ship['name'], $ship['camp'], $ship['speed_kmh'], $ship['capacity']);
    }
    return $ships;
}

function getShipInfo ($id): array {
    global $cnx;
    $stmt = $cnx->prepare("SELECT * FROM ships WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
}

function print_ships_in_database($cnx) {
    $stmt = $cnx->prepare("SELECT * FROM ships");
    $stmt->execute();
    $ships = $stmt->fetchAll();

    echo '<table>';
    echo '<tr><th>name</th><th>camp</th><th>speed_kmh</th><th>capacity</th></tr>';
    foreach ($ships as $ship) {
        echo '<tr><td>' . $ship['name'] . '</td><td>' . $ship['camp'] . '</td><td>' . $ship['speed_kmh'] . '</td><td>' . $ship['capacity'] . '</td></tr>';
    }
    echo '</table>';
}

?>