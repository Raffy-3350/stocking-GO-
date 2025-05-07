<?php
require_once '../config/database.php';
require_once '../controllers/function.php';
session_start();

$database = new Database();
$db = $database->getConnection();

$user = new user($db);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        // Capture form data
        $id = $_POST['id'];
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $date = $_POST['date'];
     

        // Call the add_product method with correct parameters
        $addProductResult = $user->editSale($id, $product_id, $price, $quantity, $date);
        
        if ($addProductResult) {
            echo "<p style='color:green;'>Sale added successfully!</p>";
            echo "<script>alert('Edit successful!'); setTimeout(function(){window.location.href = '../views/home.php'; }, 500);</script>";
        } else {
            echo "<p style='color:red;'>Failed to add Sale. Please try again.</p>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add product</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body> 
<div class="container">
  <h1>Edit Sale</h1>
  <form method="POST" action="edit_sale.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
    <label for="product_id">Product</label>
    <select name="product_id" id="product_id" required>
        <option value="">Select Product</option>
        <?php
        $products_stmt = $user->getProducts();
        while ($row = $products_stmt->fetch(PDO::FETCH_ASSOC)) { ?>
            <option value="<?php echo htmlspecialchars($row['id']); ?>" 
                    data-quantity="<?php echo htmlspecialchars($row['quantity']); ?>"
                    data-price="<?php echo htmlspecialchars($row['sell_price']); ?>">
                <?php echo htmlspecialchars($row['name']); ?>
            </option>
        <?php } ?>
    <label for="price">Price</label>
    <input type="number" name="price" id="price" placeholder="Price"="0.01" required value="<?php echo htmlspecialchars($row['price']); ?>">
    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" required value="<?php echo htmlspecialchars($row['quantity']); ?>">
    <label for="date">Date</label>
    <input type="date" name="date" id="date" required value="<?php echo htmlspecialchars($row['date']); ?>">
   <button type="submit" name="add_product">Edit Sale</button>
  </form>
    <a href="../views/home.php">Back to Home</a>
</table>
    </div>
</div>
</body>
</html>


