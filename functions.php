<?php
require_once 'db.php';


function getAllBooks() {
    global $pdo;
    
    $stmt = $pdo->query("
        SELECT books.*, categories.name AS category_name
        FROM books
        LEFT JOIN categories ON books.category_id = categories.id
        ORDER BY books.title
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getBookById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(); 
}
function addBook($data) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO books (title, author, publication_year, category_id,image) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['title'],
        $data['author'],
        $data['publication_year'],
        !empty($data['category_id']) ? $data['category_id'] : null,
        $data['image']??null,
    ]);
}
function updatebook($id, $data) {
    global $pdo;

    $sql = "UPDATE books SET title = :title, author = :author, publication_year = :year, category_id = :category_id";
    $params = [
        ':title' => $data['title'],
        ':author' => $data['author'],
        ':year' => $data['publication_year'],
        ':category_id' => $data['category_id'],
        ':id' => $id
    ];

    if (!empty($data['image'])) {
        $sql .= ", image = :image";
        $params[':image'] = $data['image'];
    }

    $sql .= " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
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

    return $errors;
}

function getAllCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, `name` FROM categories ORDER BY `name`");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getBooksByCategory($categoryId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT books.*, categories.name AS category_name
        FROM books
        LEFT JOIN categories ON books.category_id = categories.id
        WHERE books.category_id = ?
        ORDER BY books.title
    ");
    $stmt->execute([$categoryId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
