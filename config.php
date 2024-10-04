<?php
$servername = "localhost";
$username = "root";  // Dein Datenbankbenutzername
$password = "";      // Dein Datenbankpasswort
$dbname = "user_system"; // Name der Datenbank

// Verbindung zur MySQL-Datenbank herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Prüfen, ob die Verbindung erfolgreich war
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>
