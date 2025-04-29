<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Récupérer les images depuis la base de données
$stmt = $pdo->query("SELECT * FROM images ORDER BY display_order");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AD Pomme</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/logoad.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="banner"></div>
        <div class="login-icon">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="logout-button" title="Déconnexion">Déconnexion</a>
            <?php else: ?>
                <a href="login.php" title="Connexion">
                    <img src="images/profillogo.svg" alt="Connexion">
                </a>
            <?php endif; ?>
        </div>
    </header>
    
    <main>
        <div class="main-logo">
            <a href="https://ad-pomme.fr/" target="_blank">
                <img src="images/adpommelogo.svg" alt="Logo principal">
            </a>
        </div>
        <div class="image-gallery">
            <?php foreach($images as $image): ?>
                <div class="image-item">
                    <a href="<?php echo htmlspecialchars($image['url']); ?>" target="_blank">
                        <img src="images/<?php echo htmlspecialchars($image['file_name']); ?>" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>">
                        <span><?php echo htmlspecialchars($image['title']); ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="modify.php" class="modify-button">Modifier</a>
        <?php endif; ?>
    </main>
    
    <script src="js/script.js"></script>
</body>
</html>
