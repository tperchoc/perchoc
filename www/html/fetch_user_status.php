<?php
header('Content-Type: application/json');

$filePath = 'connections.txt';

if (file_exists($filePath)) {
    // Renvoie le contenu du fichier
    readfile($filePath);
} else {
    // Si le fichier n'existe pas ou ne contient pas de données
    echo json_encode([]);
}
$offlineThreshold = 15; // en secondes


foreach ($lines as $line) {
    $user = unserialize($line);
    $user['status'] = (time() - strtotime($user['timestamp'])) > $offlineThreshold ? 'déconnecté' : 'en ligne';
    $data[] = $user;
}

?>

