<?php
require_once 'functions.php';

$categories = getAllCategories();

if (!isset($_GET['id'])) {
    header('location: index.php');
    exit;
}

$id = $_GET['id'];
$book = getbookbyid($id);

if (!$book) {
    header('location: index.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedbook = $_POST;
    $errors = validatebookdata($updatedbook);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $newImageName = uniqid('book_', true) . '.' . $imageExtension;
        $destination = 'uploads/' . $newImageName;

        if (move_uploaded_file($imageTmpPath, $destination)) {
            $updatedbook['image'] = $newImageName;
        } else {
            $errors['image'] = "Erreur lors du téléchargement de l'image.";
        }
    }

    if (empty($errors)) {
        if (updatebook($id, $updatedbook)) {
            header('location: index.php?success=edit');
            exit;
        } else {
            $errors['database'] = "Erreur lors de la mise à jour du livre";
        }
    }

    $book = array_merge($book, $updatedbook);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Livre</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Modifier "<?= htmlspecialchars($book['title']) ?>"</h1>
    <a href="index.php" class="btn">Retour à la Liste</a>

    <?php if (!empty($errors)): ?>
        <div class="alert error">
            <?= implode('<br>', $errors); ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
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
            <input type="number" id="publication_year" name="publication_year" value="<?= htmlspecialchars($book['publication_year']) ?>" min="1000" max="<?= date('Y') + 5 ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie*</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= $category['id'] == ($book['category_id'] ?? '') ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Changer l'image (laisser vide pour ne pas changer)</label>
            <input type="file" id="image" name="image">
        </div>

        <?php if (!empty($book['image'])): ?>
            <div class="form-group">
                <p>Image actuelle :</p>
                <img src="uploads/<?= htmlspecialchars($book['image']) ?>" alt="Image actuelle" style="height: 100px;">
            </div>
        <?php endif; ?>

        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</div>
</body>
</html>
