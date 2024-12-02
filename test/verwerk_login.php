<?php
// Start de sessie
session_start();
 
// Databaseverbinding
$host = 'localhost';  // Database host
$dbname = 'project3'; // Naam van je database
$user = 'root';       // Database-gebruiker
$pass = '';           // Database-wachtwoord (leeg bij XAMPP)
 
// Maak een PDO-verbinding
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding met de database: " . $e->getMessage());
}
 
// Controleer of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];
 
    // Controleer of velden niet leeg zijn
    if (empty($email) || empty($wachtwoord)) {
        echo "Vul alle velden in.";
        exit;
    }
 
    // Zoek de gebruiker in de database
    $sql = "SELECT * FROM gebruiker WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if ($gebruiker) {
        // Controleer wachtwoord
        if (password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
            // Sla gebruikersinformatie op in de sessie
            $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
            $_SESSION['email'] = $gebruiker['email'];
 
            // Redirect naar een dashboard of homepage
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Onjuist wachtwoord.";
        }
    } else {
        echo "Geen account gevonden met dit e-mailadres.";
    }
} else {
    echo "Ongeldige aanvraag.";
}
?>