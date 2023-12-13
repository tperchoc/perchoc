<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

function logUserConnection($isUpdate = false) {
    $data = [
        'timestamp' => date("Y-m-d H:i:s"),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'nom' => $_SESSION['nom'],
        'prenom' => $_SESSION['prenom'],
        'classe' => $_SESSION['classe'],
        'status' => $isUpdate ? 'en ligne' : 'connecté'
    ];

    // Lire le fichier existant, ajouter les données et réécrire
    $currentData = file_exists('connections.txt') ? json_decode(file_get_contents('connections.txt'), true) : [];
    $currentData[] = $data;
    file_put_contents('connections.txt', json_encode($currentData));
}


// Gérer la requête AJAX pour mettre à jour le statut
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateStatus'])) {
    logUserConnection(true);
    exit;
}

// Vérifier si l'utilisateur est connecté
if (isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['classe'])) {
    // L'utilisateur est connecté, enregistrer sa connexion
    logUserConnection();

    // Contenu de la page
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Exécuter Python</title>
    <link rel="stylesheet" type="text/css" href="style-indexphp.css">
    <script>
        function sendAndExecute() {
            var xhr = new XMLHttpRequest();
            var url = "save_text.php"; 
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("output").innerHTML = xhr.responseText;
                }
            };

            var data = "userText=" + encodeURIComponent(document.getElementById("userText").value);
            xhr.send(data);
        }
    </script>
    <script>
        setInterval(function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("updateStatus=1");
        }, 10000); // 10 secondes pour la mise à jour du statut
    </script>
</head>
<body>
    <div>
        <h1>Plateforme Pédagogique, NSI</h1>
        <h3>console de développement python<h3>
        <textarea id="userText"></textarea>
        <button onclick="sendAndExecute()">Envoyer</button>
        <div id="output">
            <!-- Les résultats s'afficheront ici -->
        </div>
                    <button class="buttonspace" onclick="window.open('pdf.html', '_blank')">Accéder au Cours</button>

    </div>
</body>
</html>
<?php
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: login.html");
    exit;
}
?>

