<?php
// Start de sessie
session_start();
 
// Databaseverbinding
$host = 'localhost';  
$dbname = 'project3';
$user = 'root';
$pass = '';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding met de database: " . $e->getMessage());
}
 
// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
 
    // Controleer of het e-mailadres bestaat in de database
    $sql = "SELECT * FROM gebruiker WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if ($gebruiker) {
        // Genereer een unieke token
        $token = bin2hex(random_bytes(16));
        $verloopdatum = date('Y-m-d H:i:s', strtotime('+1 uur')); // Token verloopt over 1 uur
 
        // Sla de token op in de database
        $sql = "INSERT INTO wachtwoord_reset (email, token, verloopdatum) VALUES (:email, :token, :verloopdatum)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'email' => $email,
            'token' => $token,
            'verloopdatum' => $verloopdatum
        ]);
 
        // Verstuur de resetlink per e-mail
        $resetLink = "http://localhost/reset_wachtwoord.php?token=" . $token;
        $onderwerp = "Wachtwoord Reset Verzoek";
        $bericht = "Klik op de volgende link om je wachtwoord te resetten:\n\n" . $resetLink;
        $headers = "From: no-reply@voorbeeld.com";
 
        if (mail($email, $onderwerp, $bericht, $headers)) {
            echo "Een resetlink is naar je e-mailadres verzonden.";
        } else {
            echo "Fout bij het verzenden van de e-mail.";
        }
    } else {
        echo "Geen account gevonden met dit e-mailadres.";
    }
} else {
    echo "Ongeldige aanvraag.";
}
?>

heeft contextmenu