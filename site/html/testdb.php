<?php
require 'includes/db_connect.php';

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<h2>Connexion réussie !</h2>";
    echo "<p>Tables existantes : " . implode(', ', $tables) . "</p>";
} catch(PDOException $e) {
    die("<h2>Échec de connexion :</h2>" . $e->getMessage());
}
?>
