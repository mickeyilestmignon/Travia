<?php
// Table ships
// Fonctions imports des vaiseaux depuis le json
// Classe object ships, methode import vaisseau -> base de donnée

include 'include/connect.inc.php';

class ship {
    private $id;
    private $name;
    private $camp;
    private $speed_kmh;
    private $capacity;

    function __construct($id, $name, $camp, $speed_kmh, $capacity) {
        $this->id = $id;
        $this->name = $name;
        $this->camp = $camp;
        $this->speed_kmh = $speed_kmh;
        $this->capacity = $capacity;
    }

    public function getName() { return $this->name; }
    public function getCamp() { return $this->camp; }
    public function getSpeedKmh() { return $this->speed_kmh; }
    public function getCapacity() { return $this->capacity;}
    public function getId() { return $this->id; }
}

function get_json_ships($json) {
    $json = file_get_contents($json);
    $data = json_decode($json, true);
    $ships = array();
    foreach ($data as $ship) {
        $ships[] = new ship($ship['id'], $ship['name'], $ship['camp'], $ship['speed_kmh'], $ship['capacity']);
    }
    return $ships;
}

function print_ships_in_database($cnx) {
    $stmt = $cnx->prepare("SELECT * FROM ships");
    $stmt->execute();
    $ships = $stmt->fetchAll();
    foreach ($ships as $ship) {
        echo 'name = ' . $ship['name'] . ', camp = ' . $ship['camp'] . ', speed_kmh =  ' . $ship['speed_kmh'] . ', capacity =  ' . $ship['capacity'] . '<br>';
    }
}

?>