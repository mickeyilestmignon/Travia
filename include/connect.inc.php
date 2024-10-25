<?php

/*
 * création d'objet PDO de la connexion qui sera représenté par la variable $cnx
 */
$user = 'achirecesei';
$pass = '1078515';
try {
    $cnx = new PDO('mysql:host=localhost;dbname=achirecesei', $user, $pass) ;
}
catch (PDOException $e) {
    echo "ERREUR : La connexion a échouée<br>";

    /* Utiliser l'instruction suivante pour afficher le détail de erreur sur la
    * page html. Attention c'est utile pour débugger mais cela affiche des
    * informations potentiellement confidentielles donc éviter de le faire pour un
    * site en production.*/
//    echo "Error: " . $e;

}
