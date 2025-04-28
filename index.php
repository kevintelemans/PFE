<?php
require_once 'functions.php';
$books = getAllBooks(); // Correct function call without $ prefix
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (deleteBook($id)) { // Correct function call without $ prefix
        header('Location: index.php?success=delete');
        exit;
    } else {
        $error = "Erreur lors de la suppression du livre";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de livres</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ma collection de livres</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success">
                <?php 
                    if ($_GET['success'] == 'add') echo "Livre ajouté avec succès!";
                    elseif ($_GET['success'] == 'edit') echo "Livre modifié avec succès!";
                    elseif ($_GET['success'] == 'delete') echo "Livre supprimé avec succès!";
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <a href="add.php" class="btn">Ajouter un livre</a>
        
        <?php if (count($books) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Année</th>
                        <th>Genre</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><?= $book['publication_year'] ?></td>
                            <td><?= htmlspecialchars($book['genre']) ?></td>
                            <td class="actions">
                                <a href="edit.php?id=<?= $book['id'] ?>" class="btn">Modifier</a>
                                <a href="index.php?delete=<?= $book['id'] ?>" 
                                   class="btn danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucun livre dans la collection pour le moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
