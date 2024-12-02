<?php
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
 
// Controleer of de token geldig is
if (isset($_GET['token'])) {
    $token = $_GET['token'];
 
    $sql = "SELECT * FROM wachtwoord_reset WHERE token = :token AND verloopdatum > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['token' => $token]);
    $reset = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if ($reset) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nieuw_wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
 
            // Update het wachtwoord in de gebruikersdatabase
            $sql = "UPDATE gebruiker SET wachtwoord = :wachtwoord WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'wachtwoord' => $nieuw_wachtwoord,
                'email' => $reset['email']
            ]);
 
            // Verwijder de gebruikte token
            $sql = "DELETE FROM wachtwoord_reset WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $reset['email']]);
 
            echo "Je wachtwoord is succesvol gewijzigd.";
            exit;
        }
    } else {
        echo "Ongeldige of verlopen token.";
        exit;
    }
} else {
    echo "Geen token opgegeven.";
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wachtwoord Resetten</title>
</head>
<body>
<h1>Nieuw Wachtwoord Instellen</h1>
<form method="POST">
<input type="password" name="wachtwoord" placeholder="Nieuw wachtwoord" required>
<button type="submit">Wachtwoord Opslaan</button>
</form>
</body>
</html>