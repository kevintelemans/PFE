<?php
require_once 'functions.php';

$categories = getAllCategories(); // Load categories

// Handle book deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if (deleteBook($id)) {
        header('Location: index.php?success=delete');
        exit;
    } else {
        $error = "Erreur lors de la suppression du livre";
    }
}

// Load books: all or by category
if (isset($_GET['category'])) {
    $books = getBooksByCategory($_GET['category']);
} else {
    $books = getAllBooks();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de livres</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .category-filter {
            list-style: none;
            padding: 0;
            margin: 1em 0;
        }
        .category-filter li {
            display: inline;
            margin-right: 10px;
        }
        img.book-cover {
            height: 60px;
            border-radius: 4px;
        }
    </style>
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

    <h2>Filtrer par catégorie :</h2>
    <ul class="category-filter">
        <li><a href="index.php">Toutes les catégories</a></li>
        <?php foreach ($categories as $cat): ?>
            <li>
                <a href="index.php?category=<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if (count($books) > 0): ?>
        <table>
            <thead>
            <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Année</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td>
                        <?php if (!empty($book['image'])): ?>
                            <img src="uploads/<?= htmlspecialchars($book['image']) ?>" alt="Couverture" class="book-cover">
                        <?php else: ?>
                            <em>Aucune image</em>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($book['title']) ?></td>
                    <td><?= htmlspecialchars($book['author']) ?></td>
                    <td><?= $book['publication_year'] ?></td>
                    <td><?= htmlspecialchars($book['category_name'] ?? 'Aucune') ?></td>
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
        <p>Aucun livre trouvé dans cette catégorie.</p>
    <?php endif; ?>
</div>
</body>
</html>
