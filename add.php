<?php
require_once 'functions.php';

// Fetch all categories
$categoryStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Book data defaults
$errors = [];
$book = [
    'title' => '',
    'author' => '',
    'publication_year' => '',
    'category_id' => ''
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book = $_POST;
    $errors = validateBookData($book);

    if (empty($errors)) {
        if (addBook($book)) {
            header('Location: index.php?success=add');
            exit;
        } else {
            $errors['database'] = "Erreur lors de l'ajout du livre";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Ajouter un livre</h1>

    <a href="index.php" class="btn">Retour à la liste</a>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?= implode('<br>', $errors) ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label for="title">Titre*</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="author">Auteur*</label>
            <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
        </div>

        <div class="form-group">
            <label for="publication_year">Année de publication*</label>
            <input type="number" id="publication_year" name="publication_year"
                   value="<?= htmlspecialchars($book['publication_year']) ?>"
                   min="1000" max="<?= date('Y') + 5 ?>" required>
        </div>
        <div class="form-group">
            <label for="category_id">Catégorie*</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"
                        <?= $book['category_id'] == $category['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Ajouter le livre</button>
    </form>
</div>
</body>
</html>