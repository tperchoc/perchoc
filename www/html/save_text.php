<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $text = $_POST['userText'];

    // Récupération des données de la session
    $nom = isset($_SESSION['nom']) ? $_SESSION['nom'] : "Inconnu";
    $prenom = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : "Inconnu";
    $classe = isset($_SESSION['classe']) ? $_SESSION['classe'] : "Inconnu";

    // Génération du nom de fichier avec l'heure actuelle
    $heure = date("H-i-s");
    $filename = "/var/www/myappdata/" . $nom . "." . $prenom . "." . $classe . "." . $heure . ".py";

    // Enregistrement du texte dans le fichier
    file_put_contents($filename, $text);

    // Exécution du script Python et capture de la sortie et des erreurs
    $command = escapeshellcmd("python3 " . $filename) . " 2>&1";
    exec($command, $output, $return_var);

    // Affichage des résultats
    if ($return_var !== 0) {
        // S'il y a une erreur
        echo "<pre>Erreur lors de l'exécution du script Python:\n";
        echo implode("\n", $output);
        echo "</pre>";
    } else {
        // S'il n'y a pas d'erreur
        echo "<pre>";
        echo implode("\n", $output);
        echo "</pre>";
    }
}
?>

