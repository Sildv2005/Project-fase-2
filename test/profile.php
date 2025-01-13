<?php
session_start();

// Check of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header("Location: klantinlog.html"); // Redirect naar inlogpagina als niet ingelogd
    exit;
}

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

// Haal de reserveringen van de ingelogde gebruiker op
$gebruiker_id = $_SESSION['gebruiker_id'];
$sql = "SELECT * FROM reserveringen WHERE gebruiker_id = :gebruiker_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['gebruiker_id' => $gebruiker_id]);
$reserveringen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <title>Profiel</title>
</head>
<body>
    <nav>
        <img src="images/logo_small.png" alt="Coral Yachts">
        <a href="index.html">Home</a>
        <a href="zoekjacht.html">Reserveren</a>
        <a href="contact.html">Contact</a>
        <a href="overons.html">Over ons</a>
        <a href="klantinlog.html">Inloggen</a>
        <a href="logout.php">Uitloggen</a>
    </nav>

    <div class="profile-container">
        <h1>Welkom, <?php echo $_SESSION['email']; ?>!</h1>
        <h2>Je reserveringen</h2>
        
        <?php if (empty($reserveringen)): ?>
            <p>Je hebt nog geen reserveringen.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Aantal Personen</th>
                </tr>
                <?php foreach ($reserveringen as $reservering): ?>
                    <tr>
                        <td><?php echo $reservering['datum']; ?></td>
                        <td><?php echo $reservering['tijd']; ?></td>
                        <td><?php echo $reservering['aantal_personen']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
