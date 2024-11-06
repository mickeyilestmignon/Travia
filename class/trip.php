<?php
class trip {

    private int $planete_depart;
    private int $planete_arrivee;
    private int $id_ship;
    private string $day;
    private string $time;

    function __construct($planete_depart, $planete_arrivee, $id_ship, $day, $time) {
        $this->planete_depart = $planete_depart;
        $this->planete_arrivee = $planete_arrivee;
        $this->id_ship = $id_ship;
        $this->day = $day;
        $this->time = $time;
    }

    public function getPlaneteDepart(): int
    { return $this->planete_depart; }
    public function getPlaneteArrivee(): int
    { return $this->planete_arrivee; }
    public function getIdShip(): int
    { return $this->id_ship; }
    public function getDay(): string
    { return $this->day; }
    public function getTime(): string
    { return $this->time; }
}

function print_trips_in_database() {
    global $cnx;
    $stmt = $cnx->query("SELECT * FROM trips");
    $trips = $stmt->fetchAll();
    echo "<table>";
    echo "<tr><th>planete_depart</th><th>planete_arrivee</th><th>id_ship</th><th>day</th><th>time</th></tr>";
    foreach ($trips as $trip) {
        echo '<tr><td>' . $trip['planete_depart'] . '</td><td>' . $trip['planete_arrivee'] . '</td><td>' . $trip['id_ship'] . '</td><td>' . $trip['day'] . '</td><td>' . $trip['time'] . '</td></tr>';
    }
    echo "</table>";
}