<?php
// Function to import planets from a json file
// Function to print planets from the database
// Class object planet, method import planet -> database
// Method display planets from the database

use Cassandra\Map;

include_once $_SERVER['DOCUMENT_ROOT'] . '/TraviaProject/include/connect.inc.php';

class planet {
    private int $id;
    private string $name;
    private string $image;
    private string $coord;
    private int $X;
    private int $Y;
    private string $SubGridCoord;
    private float $SubGridX;
    private float $SubGridY;
    private string $region;
    private string $sector;
    private int $suns;
    private int $moons;
    private int $position;
    private int $distance;
    private int $LengthDay;
    private int $LengthYear;
    private int $diameter;
    private int $gravity;
    private array $trips;

    // constructor
    function __construct($id, $name, $image, $coord, $X, $Y, $SubGridCoord, $SubGridX, $SubGridY, $region, $sector, $suns, $moons, $position, $distance, $LengthDay, $LengthYear, $diameter, $gravity, $trips) {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->coord = $coord;
        $this->X = $X;
        $this->Y = $Y;
        $this->SubGridCoord = $SubGridCoord;
        $this->SubGridX = $SubGridX;
        $this->SubGridY = $SubGridY;
        $this->region = $region;
        $this->sector = $sector;
        $this->suns = $suns;
        $this->moons = $moons;
        $this->position = $position;
        $this->distance = $distance;
        $this->LengthDay = $LengthDay;
        $this->LengthYear = $LengthYear;
        $this->diameter = $diameter;
        $this->gravity = $gravity;
        $this->trips = $trips;
    }

    // getters

    public function getId(): int
    { return $this->id; }
    public function getName(): string
    { return $this->name; }
    public function getImage(): string
    { return $this->image; }
    public function getCoord(): string
    { return $this->coord; }
    public function getX(): int
    { return $this->X; }
    public function getY(): int
    { return $this->Y; }
    public function getSubGridCoord(): string
    { return $this->SubGridCoord; }
    public function getSubGridX(): float
    { return $this->SubGridX; }
    public function getSubGridY(): float
    { return $this->SubGridY; }
    public function getRegion(): string
    { return $this->region; }
    public function getSector(): string
    { return $this->sector; }
    public function getSuns(): int
    { return $this->suns; }
    public function getMoons(): int
    { return $this->moons; }
    public function getPosition(): int
    { return $this->position; }
    public function getDistance(): int
    { return $this->distance; }
    public function getLengthDay(): int
    { return $this->LengthDay; }
    public function getLengthYear(): int
    { return $this->LengthYear; }
    public function getDiameter(): int
    { return $this->diameter; }
    public function getGravity(): int
    { return $this->gravity; }
    public function getTrips(): array
    { return $this->trips; }
}

// Function to import planets from a json file

function get_json_planets($json): array
{
    $json = file_get_contents($json);
    $data = json_decode($json, true);
    $planets = array();
    foreach ($data as $planet) {

        $Image = '';
        if (array_key_exists('Image', $planet)) {
            $Image = $planet['Image'];
        }

        $SubGridCoord = '';
        if (array_key_exists('SubGridCoord', $planet)) {
            $SubGridCoord = $planet['SubGridCoord'];
        }

        $Coord = '';
        if (array_key_exists('Coord', $planet)) {
            $Coord = $planet['Coord'];
        }

        $planets[] = new planet($planet['Id'], $planet['Name'], $Image, $Coord, $planet['X'], $planet['Y'], $SubGridCoord, $planet['SubGridX'], $planet['SubGridY'], $planet['Region'], $planet['Sector'], $planet['Suns'], $planet['Moons'], $planet['Position'], $planet['Distance'], $planet['LengthDay'], $planet['LengthYear'], $planet['Diameter'], $planet['Gravity'], $planet['trips']);
    }
    return $planets;
}

function print_planets_in_database() {
    global $cnx;
    $stmt = $cnx->prepare("SELECT * FROM planets");
    $stmt->execute();
    $planets = $stmt->fetchAll();

    echo '<table>';
    echo '<tr><th>image</th><th>name</th><th>image</th><th>coord</th><th>X</th><th>Y</th><th>SubGridCoord</th><th>SubGridX</th><th>SubGridY</th><th>region</th><th>sector</th><th>suns</th><th>moons</th><th>position</th><th>distance</th><th>LengthDay</th><th>LengthYear</th><th>diameter</th><th>gravity</th><th>trips</th></tr>';
    foreach ($planets as $planet) {
        echo '<tr><td><img src="https://static.wikia.nocookie.net/starwars/images/'.substr(md5($planet['image']), 0, 1).'/'.substr(md5($planet['image']), 0, 2).'/'.$planet['image'].'" alt=""></td><td>' . $planet['name'] . '</td><td>' . $planet['image'] . '</td><td>' . $planet['coord'] . '</td><td>' . $planet['X'] . '</td><td>' . $planet['Y'] . '</td><td>' . $planet['SubGridCoord'] . '</td><td>' . $planet['SubGridX'] . '</td><td>' . $planet['SubGridY'] . '</td><td>' . $planet['region'] . '</td><td>' . $planet['sector'] . '</td><td>' . $planet['suns'] . '</td><td>' . $planet['moons'] . '</td><td>' . $planet['position'] . '</td><td>' . $planet['distance'] . '</td><td>' . $planet['LengthDay'] . '</td><td>' . $planet['LengthYear'] . '</td><td>' . $planet['diameter'] . '</td><td>' . $planet['gravity'] . '</td><td>' . $planet['trips'] . '</td></tr>';
    }
    echo '</table>';
}