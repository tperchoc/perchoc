<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $classe = $_POST['classe'];

    // Vérifiez si l'utilisateur est l'administrateur
    if ($nom == "admin" && $prenom == "admin" && $classe == "admin") {
        // L'utilisateur est l'administrateur
        $_SESSION['admin'] = true;  // Mettre en place une session d'administrateur
        header("Location: admin.php");  // Redirection vers la page d'administration
        exit;
    } else {
        // L'utilisateur n'est pas l'administrateur
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['classe'] = $classe;

        // Redirection vers la page principale
        header("Location: index.php");
        exit;
    }
}
?>

