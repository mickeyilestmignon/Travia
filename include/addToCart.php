<?php
if (isset($_COOKIE['cart']) && isset($_GET['departure']) && isset($_GET['arrival']) && isset($_GET['ship'])) {
    $cart = unserialize($_COOKIE['cart']);
    $cart[] = [
        'departure' => $_GET['departure'],
        'arrival' => $_GET['arrival'],
        'ship' => $_GET['ship']
    ];
    setcookie('cart', serialize($cart), time() + 7200, '/');
}
header('Location: ' . $_SERVER['HTTP_REFERER']);