<?php
// Assurez-vous que seuls les administrateurs authentifiés peuvent accéder à ce script
session_start();

$filePath = 'connections.txt';

// Vérifiez si le fichier existe, puis videz-le
if (file_exists($filePath)) {
    // Videz le contenu du fichier
    file_put_contents($filePath, '');
    echo 'Le fichier des connexions a été vidé.';
} else {
    echo 'Le fichier des connexions n’existe pas.';
}
?>

