<?php
// Start der Session (für spätere Verwendung, falls benötigt)
session_start();
require_once 'config.php'; // Datenbankverbindung einbinden

// Prüfen, ob das Formular über POST gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validierung der Benutzereingaben
    $errors = [];

    if (!preg_match("/^[a-zA-Z]{2,}$/", $firstname)) {
        $errors[] = "Der Vorname muss aus mindestens zwei Buchstaben bestehen und darf keine Sonderzeichen oder Zahlen enthalten.";
    }

    if (!preg_match("/^[a-zA-Z]{2,}$/", $lastname)) {
        $errors[] = "Der Nachname muss aus mindestens zwei Buchstaben bestehen und darf keine Sonderzeichen oder Zahlen enthalten.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
    }

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
        $errors[] = "Das Passwort muss mindestens acht Zeichen lang sein und sowohl Buchstaben als auch Zahlen enthalten.";
    }

    // Wenn es keine Fehler gibt, wird der Benutzer registriert
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Benutzer in die Datenbank einfügen
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registrierung erfolgreich. Sie können sich jetzt anmelden.";
                header("Location: index.html"); // Umleitung zur Login-Seite
            } else {
                $errors[] = "Es gab ein Problem bei der Registrierung. Versuchen Sie es später erneut.";
            }

            $stmt->close();
        }
    }

    // Fehler anzeigen
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>
