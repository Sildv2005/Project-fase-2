<?php
// Start de sessie
session_start();

// Databaseverbinding
$host = 'localhost';
$dbname = 'project6';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding met de database: " . $e->getMessage());
}

// Verwerken van de registratie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];
    $naam = $_POST['naam'];

    // Controleer of velden niet leeg zijn
    if (empty($email) || empty($wachtwoord) || empty($naam)) {
        echo "Vul alle velden in.";
        exit;
    }

    // Controleer of het e-mailadres al bestaat
    $sql = "SELECT * FROM gebruiker WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker) {
        echo "Er is al een account met dit e-mailadres.";
        exit;
    }

    // Wachtwoord hashen
    $hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

    // Voeg de gebruiker toe aan de database
    $sql = "INSERT INTO gebruiker (email, wachtwoord, naam) VALUES (:email, :wachtwoord, :naam)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'email' => $email,
        'wachtwoord' => $hashed_wachtwoord,
        'naam' => $naam
    ]);

    echo "Registratie succesvol! Je kunt nu inloggen.";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <title>Registreren</title>
</head>
<body>
    <nav>
        <img src="images/logo_small.png" alt="Coral Yachts">
        <a href="index.html">Home</a>
        <a href="zoekjacht.html">Reserveren</a>
        <a href="contact.html">Contact</a>
        <a href="overons.html">Over ons</a>
        <a href="klantinlog.html">Inloggen</a>
    </nav>

    <div class="register-container">
        <h1>Registreren</h1>
        <form action="register.php" method="POST">
            <input type="text" name="naam" placeholder="Je naam" required>
            <input type="email" name="email" placeholder="E-mailadres" required>
            <input type="password" name="wachtwoord" placeholder="Wachtwoord" required>
            <button type="submit">Registreren</button>
        </form>
        <p>Heb je al een account? <a href="klantinlog.html">Inloggen</a></p>
    </div>
</body>
</html>
