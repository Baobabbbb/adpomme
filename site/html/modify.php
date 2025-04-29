<?php
session_start();
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Rediriger si non connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer les images
$stmt = $pdo->query("SELECT * FROM images ORDER BY display_order");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traiter les différentes actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ajout d'une nouvelle image
    if (isset($_POST['action']) && $_POST['action'] == 'add_new') {
        $newTitle = $_POST['title'] ?? '';
        $newUrl = $_POST['url'] ?? '';
        $displayOrder = count($images) + 1;

        if (!empty($newTitle) && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file = $_FILES['image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (in_array($file['type'], $allowedTypes) && $file['size'] < 5000000) {
                $newFileName = uniqid() . '-' . $file['name'];
                
                if (move_uploaded_file($file['tmp_name'], "images/" . $newFileName)) {
                    $stmt = $pdo->prepare("INSERT INTO images (title, file_name, url, display_order) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$newTitle, $newFileName, $newUrl, $displayOrder]);
                    header("Location: modify.php?success=1");
                    exit;
                }
            }
        }
    }
    // Suppression d'image
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $imageId = $_POST['image_id'] ?? '';
        
        $stmt = $pdo->prepare("SELECT file_name FROM images WHERE id = ?");
        $stmt->execute([$imageId]);
        $image = $stmt->fetch();
        
        if ($image) {
            if (file_exists("images/" . $image['file_name'])) {
                unlink("images/" . $image['file_name']);
            }
            $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
            $stmt->execute([$imageId]);
            
            // Réorganiser l'ordre
            $pdo->query("SET @rank = 0");
            $pdo->query("UPDATE images SET display_order = (@rank:=@rank+1) ORDER BY display_order");
            header("Location: modify.php?success=1");
            exit;
        }
    }
    // Réorganisation
    elseif (isset($_POST['action']) && in_array($_POST['action'], ['move_up', 'move_down'])) {
        $imageId = $_POST['image_id'] ?? '';
        $direction = ($_POST['action'] == 'move_up') ? 'up' : 'down';
        
        $stmt = $pdo->prepare("SELECT id, display_order FROM images WHERE id = ?");
        $stmt->execute([$imageId]);
        $currentImage = $stmt->fetch();
        
        if ($currentImage) {
            $newOrder = ($direction == 'up') ? $currentImage['display_order'] - 1 : $currentImage['display_order'] + 1;
            
            $stmt = $pdo->prepare("SELECT id FROM images WHERE display_order = ?");
            $stmt->execute([$newOrder]);
            $swapImage = $stmt->fetch();
            
            if ($swapImage) {
                $stmt = $pdo->prepare("UPDATE images SET display_order = ? WHERE id = ?");
                $stmt->execute([$currentImage['display_order'], $swapImage['id']]);
                $stmt->execute([$newOrder, $currentImage['id']]);
            }
            header("Location: modify.php");
            exit;
        }
    }
    // Modification d'image existante
    else {
        $imageId = $_POST['image_id'] ?? '';
        $newTitle = $_POST['title'] ?? '';
        $newUrl = $_POST['url'] ?? '';
        
        if (!empty($imageId) && !empty($newTitle)) {
            $stmt = $pdo->prepare("UPDATE images SET title = ?, url = ? WHERE id = ?");
            $stmt->execute([$newTitle, $newUrl, $imageId]);
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $file = $_FILES['image'];
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (in_array($file['type'], $allowedTypes) && $file['size'] < 5000000) {
                    $stmtOld = $pdo->prepare("SELECT file_name FROM images WHERE id = ?");
                    $stmtOld->execute([$imageId]);
                    $oldImage = $stmtOld->fetch();
                    
                    $newFileName = uniqid() . '-' . $file['name'];
                    
                    if (move_uploaded_file($file['tmp_name'], "images/" . $newFileName)) {
                        if ($oldImage && file_exists("images/" . $oldImage['file_name'])) {
                            unlink("images/" . $oldImage['file_name']);
                        }
                        $stmtUpdate = $pdo->prepare("UPDATE images SET file_name = ? WHERE id = ?");
                        $stmtUpdate->execute([$newFileName, $imageId]);
                    }
                }
            }
            header("Location: modify.php?success=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="images/logoad.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="banner"></div>
        <div class="login-icon">
            <a href="logout.php" class="logout-button" title="Déconnexion">Déconnexion</a>
        </div>
    </header>
    
    <main class="modify-page">
        <h1>Modifier</h1>
        <a href="index.php" class="back-button-haut">Retour à l'accueil</a>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="success">Modifications enregistrées avec succès !</div>
        <?php endif; ?>
        
        <div class="add-new-section">
            <h2>Ajouter une nouvelle image</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_new">
                
                <div class="form-group">
                    <label>Titre</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Lien</label>
                    <input type="text" name="url">
                </div>
                
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" required>
                </div>
                
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <div class="image-list">
            <?php foreach($images as $image): ?>
                <div class="image-edit-item">
                    <img src="images/<?php echo htmlspecialchars($image['file_name']); ?>" 
                         alt="<?php echo htmlspecialchars($image['title']); ?>">
                    
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                        
                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($image['title']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Lien</label>
                            <input type="text" name="url" value="<?php echo htmlspecialchars($image['url']); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Nouvelle image</label>
                            <input type="file" name="image">
                        </div>
                        
                        <div class="control-buttons">
                            <button type="submit">Enregistrer</button>
                            <div class="order-controls">
                                <button type="submit" name="action" value="move_up">▲</button>
                                <button type="submit" name="action" value="move_down">▼</button>
                                <button type="submit" name="action" value="delete" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette image?')">🗑️</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        
        <a href="index.php" class="back-button-bas">Retour à l'accueil</a>
    </main>
</body>
</html>
