<?php
require_once 'functions.php';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </div>

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
            <input type="number" id="publication_year" name="publication_year" value="<?= htmlspecialchars($book['publication_year']) ?>" min="1000" max="<?= date('Y') + 5 ?>" required>
        </div>
        <div class="form-group">
            <label for="genre">Genre*</label>
            <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($book['genre']) ?>" required>
            <small>Ex: roman, science-fiction</small>
        </div>
        <button type="submit" class="btn">Mettre à jour</button>
    </form>
</body>
</html>
