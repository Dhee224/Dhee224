<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'study_planner');

// Créer la connexion
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérifier la connexion
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Fonction utilitaire pour exécuter des requêtes
function executeQuery($sql, $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            elseif (is_bool($param)) $types .= 'i';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt;
}

// Fonction pour obtenir les résultats
function getResults($stmt) {
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Fonction pour obtenir une seule ligne
function getRow($stmt) {
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fonction pour obtenir le nombre de lignes affectées
function getAffectedRows() {
    global $conn;
    return $conn->affected_rows;
}

// Fonction pour obtenir le dernier ID inséré
function getLastInsertId() {
    global $conn;
    return $conn->insert_id;
}
?>
