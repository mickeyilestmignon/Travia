<?php
class cart {
    private String $id;
    private int $departure;
    private int $arrival;
    private int $ship;
    private int $quantity;

    public function __construct(String $id, int $departure, int $arrival, int $ship, int $quantity) {
        $this->id = $id;
        $this->departure = $departure;
        $this->arrival = $arrival;
        $this->ship = $ship;
        $this->quantity = $quantity;
    }

    public function getId(): String
    { return $this->id; }

    public function getDeparture(): int
    { return $this->departure; }

    public function getArrival(): int
    { return $this->arrival; }

    public function getShip(): int
    { return $this->ship; }

    public function getQuantity(): int
    { return $this->quantity; }

    public function addQuantity() {
        $this->quantity++;
    }
}