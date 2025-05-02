<?php
require_once 'functions.php';
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
   
    $stmt = $pdo->prepare("INSERT INTO categories (name ) VALUES (:name )");
    $stmt->execute([
        ':name' => $name,
        
    ]);

    echo "Category created successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Category</title>
</head>
<body>
    <h1>Create a New Category</h1>
    <form method="POST" action="create_category.php">
        <label>Category Name:</label><br>
        <input type="text" name="name" required><br><br>

       

        <button type="submit" name="submit">Create Category</button>
    </form>
</body>
</html>