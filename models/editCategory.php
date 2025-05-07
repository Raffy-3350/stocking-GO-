<?php
require_once '../config/database.php';
require_once '../controllers/function.php';
session_start();
$database = new Database();     
$db = $database->getConnection();
$user = new user($db);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
       
        
        if ($user->editCategory($id, $name)) {
            echo "<script>alert('Edit successful!'); setTimeout(function(){window.location.href = 'category.php'; }, 1000);</script>";
        } else {
            echo "Edit failed!";
        }
    }
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $user->members();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Category</h1>
        <form method="POST" action="editCategory.php">
            <input type="hidden" name="id" value="">
            <input type="text" name="name" placeholder="Edit Category" value="" required>
            <button type="submit" name="edit">Edit</button>
        </form>
    </div>
</body>
</html>