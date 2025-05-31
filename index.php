
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title> 🏨 Réservation Hôtel </title>
       <link rel="stylesheet" href="style/style.css">
</head>
<body>

<?php
include_once "include/config.php";

// Connexion à la base de données
$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    echo "Échec de la connexion à la base de données MySQL : " . $mysqli->connect_error;
    exit();
} else {
    echo "<p class='success'> La connexion a bien fonctionné !</p>";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $mysqli->real_escape_string($_POST['nom']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $arrivee = $_POST['arrivee'];
    $depart = $_POST['depart'];
    $personnes = (int)$_POST['personnes'];

    $insert_sql = "INSERT INTO reservation (nom, email, date_arrivee, date_depart, personnes)
                   VALUES ('$nom', '$email', '$arrivee', '$depart', $personnes)";

    if ($mysqli->query($insert_sql)) {
        echo "<p class='success'>Réservation enregistrée avec succès !</p>";
    } else {
        echo "<p class='error'>Erreur lors de la réservation : " . $mysqli->error . "</p>";
    }
}

// Récupération des réservations
$sql = "SELECT * FROM reservation ORDER BY date_arrivee DESC";
$result = $mysqli->query($sql);
$reservations = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}
?>
    <h1>Hôtel Le Paradis</h1>
    <p>Bienvenue à l’Hôtel Le Paradis, votre séjour de rêve commence ici </p>
    <img src="img/hotel.jpg" alt="Photo de l'hôtel" style="width:300px;"/>

    <h2>Réserver une chambre</h2>



    <form method="POST" action="">
        <label>Nom : <input type="text" name="nom" required></label><br><br>
        <label>Email : <input type="email" name="email" required></label><br><br>
        <label>Date d’arrivée : <input type="date" name="arrivee" required></label><br><br>
        <label>Date de départ : <input type="date" name="depart" required></label><br><br>
        <label>Nombre de personnes : <input type="number" name="personnes" min="1" required></label><br><br>
        <button type="submit">Réserver</button>
    </form>

    <h2>Liste des réservations</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Date d'arrivée</th>
                <th>Date de départ</th>
                <th>Personnes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($reservations as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['nom']) ?></td>
                    <td><?= htmlspecialchars($res['email']) ?></td>
                    <td><?= htmlspecialchars($res['date_arrivee']) ?></td>
                    <td><?= htmlspecialchars($res['date_depart']) ?></td>
                    <td><?= (int)$res['personnes'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>
