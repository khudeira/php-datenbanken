<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validierung der E-Mail und des Passworts
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
        $sql = "SELECT id, firstname, password FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            // Überprüfen, ob der Benutzer existiert
            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $firstname, $hashed_password);
                $stmt->fetch();

                // Passwort überprüfen
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['firstname'] = $firstname;

                    header("Location: welcome.php");
                } else {
                    echo "<p>Falsches Passwort.</p>";
                }
            } else {
                echo "<p>E-Mail-Adresse nicht gefunden.</p>";
            }

            $stmt->close();
        }
    } else {
        echo "<p>Ungültige Eingaben.</p>";
    }
}
?>
