<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <link rel="stylesheet" type="text/css" href="style-admin.css">
    <script>
        function updateDisplay(users) {
            var uniqueUsers = {};
            var html = "";
            
            // Filtrer les utilisateurs pour ne garder que le dernier statut de chaque utilisateur
            users.forEach(function(user) {
                uniqueUsers[user.nom + user.prenom + user.classe] = user;
            });
            
            // Créer le HTML pour chaque utilisateur
            Object.values(uniqueUsers).forEach(function(user) {
                var statusClass = user.status === 'en ligne' ? 'online' : 'offline';
                html += "<tr class='" + statusClass + "'>" +
                        "<td>" + escapeHTML(user.nom) + "</td>" +
                        "<td>" + escapeHTML(user.prenom) + "</td>" +
                        "<td>" + escapeHTML(user.classe) + "</td>" +
                        "<td>" + escapeHTML(user.status) + "</td>" +
                        "</tr>";
            });
            
            document.getElementById("userTableBody").innerHTML = html;
        }

        function fetchUserStatus() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var users = JSON.parse(this.responseText);
                    updateDisplay(users);
                }
            };
            xhr.open("GET", "fetch_user_status.php", true);
            xhr.send();
        }

        function clearConnections() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Après avoir vidé le fichier, rafraîchissez l'affichage
                    fetchUserStatus();
                }
            };
            xhr.open("GET", "clear_connections.php", true);
            xhr.send();
        }

        function escapeHTML(text) {
            if (typeof text === 'undefined' || text === null) {
                return '';
            }
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Mise à jour toutes les 10 secondes
        setInterval(fetchUserStatus, 10000);
        window.onload = fetchUserStatus; // Charge également au démarrage
    </script>
</head>
<body>
    <div class="container">
        <h1>Statut des Utilisateurs</h1>
        <button onclick="clearConnections()">Vider les connexions</button>
        <table>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Classe</th>
                <th>Statut</th>
            </tr>
            <tbody id="userTableBody">
                <!-- Les données des utilisateurs seront insérées ici -->
            </tbody>
        </table>
    </div>
</body>
</html>

