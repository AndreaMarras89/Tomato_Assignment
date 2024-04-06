<?php
$servername = "127.0.0.1:3306"; // Indirizzo del server MySQL
$username = "root"; // Nome utente del database
$password = ""; // Password del database MYSQL irOncl@d2
$dbname = "WorkDB"; // Nome del database

// Crea una connessione 
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

?>