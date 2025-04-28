<?php
require_once 'db.php';

function getAllBooks() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM books ORDER BY title");
    return $stmt->fetchAll();
}

function getBookById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); 
}

function addBook($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO books (title, author, publication_year, genre) VALUES (?, ?, ?, ?)");
    return $stmt->execute([
        $data['title'],
        $data['author'],
        $data['publication_year'],
        $data['genre'],
    ]);
}

function updateBook($id, $data) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, publication_year = ?, genre = ? WHERE id = ?");
    return $stmt->execute([
        $data['title'],
        $data['author'],
        $data['publication_year'],
        $data['genre'],
        $id
    ]);
}

function deleteBook($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
    return $stmt->execute([$id]);
}

function validateBookData($data) {
    $errors = [];
    if (empty($data['title'])) {
        $errors['title'] = 'Le titre est obligatoire';
    }
    if (empty($data['author'])) {
        $errors['author'] = 'L\'auteur est obligatoire';
    }
    
    if (empty($data['publication_year']) || !is_numeric($data['publication_year'])) {
        $errors['publication_year'] = 'L\'année de publication doit être un nombre';
    } elseif ((int)$data['publication_year'] < 1000 || (int)$data['publication_year'] > date('Y') + 5) {
        $errors['publication_year'] = 'L\'année de publication est invalide';
    }
    if (empty($data['genre'])) {
        $errors['genre'] = 'Le genre est obligatoire';
    }
    return$errors;
}
?>
