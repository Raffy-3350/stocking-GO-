<?php
require_once '../config/database.php';
require_once '../controllers/function.php';
session_start();

$database = new Database();
$db = $database->getConnection();
//category
$user = new user($db);
if (isset($_POST['add']) && isset($_POST['name']) && !empty($_POST['name'])) {
      $name = trim($_POST['name']); // Get and sanitize the category name
      try {
          if ($user->addCategory($name)) {
              $_SESSION['success'] = "Category added successfully!";
              header("Location: " . $_SERVER['PHP_SELF']);
              exit;
          } else {
              $_SESSION['error'] = "Error adding category!";
          }
      } catch (PDOException $e) {
          $_SESSION['error'] = "Database error: " . $e->getMessage();
      }
  }
  //product
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['add_product'])) {
          // Capture form data
          $name = $_POST['product_name'];
          $photo = $_FILES['product_photo']['name'];
          $quantity = $_POST['product_quantity'];
          $buy_price = $_POST['buy_price'];
          $sell_price = $_POST['sell_price'];
          $category_name = $_POST['product_category'];
          $stock = $_POST['stock_status']; // Get stock status from form
  
          // Call the add_product method with correct parameters
          $addProductResult = $user->add_product($name, $photo, $quantity, $buy_price, $sell_price, $stock, $category_name);
          
          if ($addProductResult) {
              echo "<p style='color:green;'>Product added successfully!</p>";
          } else {
              echo "<p style='color:red;'>Failed to add product. Please try again.</p>";
          }
      }
  }
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['add'])) {
          try {
              $product_id = $_POST['product_id'];
              $quantity = intval($_POST['quantity']); // Convert to integer
              $price = floatval($_POST['price']);    // Convert to float
              $date = $_POST['date'] ?? date('Y-m-d');
  
              if ($quantity <= 0) {
                  throw new Exception("Quantity must be greater than 0");
              }
  
              $addSaleResult = $user->addSale($product_id, $price, $quantity, $date);
              if ($addSaleResult) {
                  echo "<p class='success'>Sale recorded successfully!</p>";
              }
          } catch (Exception $e) {
              echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
          }
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Stocking Go!</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
      <link rel="stylesheet" href="../assets/css/styled.css">
      <link rel="shortcut icon" href="../assets/images/site-logo.png" type="image/x-icon">
</head>

<body>
      <!-- Menu button -->
      <button class="menu-btn" id="menu-btn" onclick="toggleNavbar()">
            <img src="../assets/images/logo-nav.png" alt="">
      </button>

      <!-- Admin Dropdown -->
      <div class="admin-dropdown" id="admin-dropdown">
            <button class="admin-btn" onclick="toggleAdminMenu()">Admin <i class="fas fa-caret-down"></i></button>
            <div class="dropdown-content" id="dropdown-content">
            <a href="../controllers/logout.php"><i class="fas fa-sign-out-alt"></i> Log out</a>
            </div>
      </div>

      <!-- Navbar -->
      <div class="navbar" id="navbar">
            <div class="navbar-logo" id="navbar-logo" onclick="toggleNavbar()">
                  <img src="../assets/images/logo-nav.png" alt="Logo">
            </div>
            <a href="#" onclick="showSection('dashboard')" style="margin-top: 4rem;"><i
                        class="fas fa-tachometer-alt"></i>
                  Dashboard</a>
            <a href="#" onclick="showSection('members')"><i class="fas fa-users"></i> Members</a>
            <a href="#" onclick="showSection('categories')"><i class="fas fa-tags"></i> Categories</a>
            <a href="#" onclick="showSection('product')"><i class="fas fa-boxes"></i> Product</a>
            <a href="#" onclick="showSection('sales')"><i class="fas fa-shopping-cart"></i> Sales</a>
            <a href="#" onclick="showSection('reports')"><i class="fas fa-chart-line"></i> Reports</a>
      </div>

      <!-- Sections -->
      <div class="section active" id="dashboard">
            <!-- Dashboard -->
            <div class="wrappered">
                  <div class="row">
                        <div class="container-dash">
                              <div class="back">
                                    <p><br><br><br>&emsp;&emsp;&emsp;&emsp;<?php 
    $query = "SELECT COUNT(*) as total FROM users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['total']; ?>&ensp;<br>&emsp;&emsp;&emsp;Members</p>
                              </div>
                              <div class="front1"><i class="fa fa-users"
                                          style="font-size: 74px; display: flex ; justify-content: center; margin-top: 45%;"></i>
                              </div>
                        </div>
                        <div class="container-dash">
                              <div class="back">
                                    <p><br><br><br>&emsp;&emsp;&emsp;&emsp;&ensp;<?php 
    $query = "SELECT COUNT(*) as total FROM categories";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['total']; ?><br>&emsp;&emsp;&nbsp;Categories</p>
                              </div>
                              <div class="front2"><i class="fa fa-cubes"
                                          style="font-size: 74px; display: flex ; justify-content: center; margin-top: 45%;"></i>
                              </div>
                        </div>
                  </div>

                  <div class="row">
                        <div class="container-dash">
                              <div class="back">
                                    <p><br><br><br>&emsp;&emsp;&emsp;&emsp;&ensp;<?php 
    $query = "SELECT COUNT(*) as total FROM products";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['total']; ?><br>&emsp;&emsp;&emsp;Products</p>
                              </div>
                              <div class="front3"><i class="fa fa-shopping-cart"
                                          style="font-size: 74px; display: flex ; justify-content: center; margin-top: 45%;"></i>
                              </div>
                        </div>
                        <div class="container-dash">
                              <div class="back">
                                    <p><br><br><br>&emsp;&emsp;&emsp;&emsp;&ensp;<?php 
    $query = "SELECT COUNT(*) as total FROM sales";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['total']; ?><br>&emsp;&emsp;&emsp;&ensp;Sales</p>
                              </div>
                              <div class="front4"><i class="fa fa-usd"
                                          style="font-size: 74px; display: flex ; justify-content: center; margin-top: 45%;"></i>
                              </div>
                        </div>
                  </div>

                  <div class="row">
                        <div class="container-dash">
                              <div class="back">
                                    <p><br><br><br>&emsp;&emsp;&emsp;&emsp;&ensp;<?php 
    $query = "SELECT COUNT(*) as total FROM sales";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $result['total']; ?><br>&emsp;&emsp;&emsp;Reports</p>
                              </div>
                              <div class="front5"><i class="fa fa-bar-chart"
                                          style="font-size: 74px; display: flex ; justify-content: center; margin-top: 45%;"></i>
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <div class="section" id="members">
            <!-- MEMBERS -->
            <div class="container">
                  <h2>Stocking Go!</h2>
                  <table class="employee-table">
                        <thead>
                              <tr>
                                    <th class="employee-header">No.</th>
                                    <th class="employee-header">Last Name</th>
                                    <th class="employee-header">First Name</th>
                                    <th class="employee-header">Employee ID</th>
                                    <th class="employee-header">Edit</th>
                              </tr>
                        </thead>
                        <tbody>
                        <?php
       $stmt = $user->members();
       while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td class='employee-cell'>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td class='employee-cell'>" . htmlspecialchars($row['first_name']) . "</td>";
    echo "<td class='employee-cell'>" . htmlspecialchars($row['last_name']) . "</td>";
    echo "<td class='employee-cell'>" . htmlspecialchars($row['employee_id']) . "</td>";
    echo "<td class='icons employee-cell'>";
    echo "<span class='edit-icon'><a href='../models/edit.php?id=" . htmlspecialchars($row['id']) . "\"'>&#9998;</a></span>";
    echo "<span class='delete-icon'><a href='../models/delete.php?id=" . htmlspecialchars($row['id']) . "\"'>&#128465;</a></span>";
    echo "</td>";
    echo "</tr>";
}
?>                            
                        </tbody>
                  </table>
            </div>
      </div>

      <!-- CATEGORIES -->
      <div class="section" id="categories">
            <div class="container-categ">
                  <div class="add-category">
                        <form action="home.php" method="POST">
                        <h3><span>&#x1F5C3;</span> ADD NEW CATEGORY</h3>
                        <input type="text" name="name" placeholder="Category Name" required>
                        <button class="btns round" type="submit" name="add">+</button>
                        </form>
                  </div>

                  <div class="category-table">
    <h3><span>&#x1F5C3;</span> CATEGORIES</h3>
    <table class="table">
        <tr>
            <th class="table-header">#</th>
            <th class="table-header">Category</th>
            <th class="table-header">Edit</th>
        </tr>
        <?php 
        // Fetch categories for displaying in the table
        $categories_stmt = $user->getCategory();
        while ($row = $categories_stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td class='table-cell'>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td class='table-cell'>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td class='table-cell actions'>";
            echo "<button class='btns' title='Edit' onclick='window.location.href=\"../models/editCategory.php?id=" . htmlspecialchars($row['id']) . "\"'>&#9998;</button>";
            echo "<button class='btns' title='Delete' onclick='window.location.href=\"../models/deleteCategory.php?id=" . htmlspecialchars($row['id']) . "\"'>&#128465;</button>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
            </div>
      </div>

      <!-- PRODUCTS -->
      <div class="section" id="product">
    <div class="product-panel">
        <h2>ADD NEW PRODUCT</h2>
        <div class="form-groups">
            <form method="POST" action="home.php" enctype="multipart/form-data">
                <select name="product_category" required>
                    <option value="">Product Category</option>
                    <?php
                    $stmt = $user->getCategory();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . htmlspecialchars($row['name']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                    ?>
                </select>
                <input type="text" name="product_name" placeholder="Product Name" required>
                <input type="number" name="product_quantity" placeholder="Product Quantity" required>
                <input type="number" name="buy_price" placeholder="Buying Price" step="0.01" required>
                <input type="number" name="sell_price" placeholder="Selling Price" step="0.01" required>
                <i class="fas fa-camera"></i><input type="file" name="product_photo" accept="image/*" required>
                <select name="stock_status" required>
                    <option value="In Stock">In Stock</option>
                    <option value="Out of Stock">Out of Stock</option>
                    <option value="Low Stock">Low Stock</option>
                </select>
                <button type="submit" name="add_product" class="add-btn">ADD PRODUCT</button>
            </form>
        </div>
    </div>

    <div class="product-list">
        <h2>PRODUCTS</h2>
        <table class="product-table">
            <tr>
                <th class="product-header">#</th>
                <th class="product-header">Photo</th>
                <th class="product-header">Product Name</th>
                <th class="product-header">Category</th>
                <th class="product-header">In-stock</th>
                <th class="product-header">Buying Price</th>
                <th class="product-header">Selling Price</th>
                <th class="product-header">Date Added</th>
                <th class="product-header">Action</th>
            </tr>
            <?php
            $stmt = $user->getProducts();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td class='product-cell'>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td class='product-cell'>";
                if (!empty($row['image_path'])) {
                    echo "<img src='../uploads/" . htmlspecialchars($row['image_path']) . "' alt='' style='width: 50px; height: 50px;'>";
                } else {
                    echo "No image";
                }
                echo "</td>";
                echo "<td class='product-cell'>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td class='product-cell'>" . htmlspecialchars($row['category_name'] ?? 'N/A') . "</td>";
                echo "<td class='product-cell'>" . htmlspecialchars($row['stock']) . "</td>";
                echo "<td class='product-cell'>$" . htmlspecialchars($row['buy_price']) . "</td>";
                echo "<td class='product-cell'>$" . htmlspecialchars($row['sell_price']) . "</td>";
                echo "<td class='product-cell'>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td class='product-cell'>";
                echo "<button class='icon-btn' onclick='window.location.href=\"../models/edit_product.php?id=" . htmlspecialchars($row['id']) . "\"'>✏️</button>";
                echo "<button class='icon-btn' onclick='window.location.href=\"../models/delete_product.php?id=" . htmlspecialchars($row['id']) . "\"'>🗑️</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

      <!-- SALES -->
      <div class="section" id="sales">
    <div class="panel">
        <h2>Add New Sale</h2>
        <form method="POST" action="home.php" class="sales-form">
            <table class="sales-table">
                <tr>
                    <th class="sales-header">Product</th>
                    <th class="sales-header">Price</th>
                    <th class="sales-header">Quantity</th>
                    <th class="sales-header">Total</th>
                    <th class="sales-header">Date</th>
                    <th class="sales-header">Action</th>
                </tr>
                <tr>
                    <td class="sales-cell">
                        <select name="product_id" id="product_id" class="sales-select" required>
                            <option value="">Select Product</option>
                            <?php
                            $products_stmt = $user->getProducts();
                            while ($row = $products_stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . htmlspecialchars($row['id']) . "' 
                                    data-quantity='" . htmlspecialchars($row['quantity']) . "'
                                    data-price='" . htmlspecialchars($row['sell_price']) . "'>"
                                    . htmlspecialchars($row['name']) . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td class="sales-cell">
                        <input type="number" name="price" id="price" class="sales-input" step="0.01" required>
                    </td>
                    <td class="sales-cell">
                        <input type="number" name="quantity" id="quantity" class="sales-input" min="1" required>
                    </td>
                    <td class="sales-cell">
                        <input type="number" name="total" id="total" class="sales-input" step="0.01" readonly>
                    </td>
                    <td class="sales-cell">
                        <input type="date" name="date" id="date" class="sales-input" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </td>
                    <td class="sales-cell">
                        <button type="submit" name="add" class="icon-btn">➕</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <div class="panel">
        <h2>Sales History</h2>
        <table class="sales-table">
            <tr>
                <th class="sales-header">#</th>
                <th class="sales-header">Photo</th>
                <th class="sales-header">Product Name</th>
                <th class="sales-header">Quantity</th>
                <th class="sales-header">Total Sales</th>
                <th class="sales-header">Date</th>
                <th class="sales-header">Actions</th>
            </tr>
            <?php
            $stmt = $user->getSales();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td class='sales-cell'>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td class='sales-cell'>";
                if (!empty($row['image_path'])) {
                    echo "<img src='../uploads/" . htmlspecialchars($row['image_path']) . 
                         "' alt='' style='width: 50px; height: 50px;'>";
                } else {
                    echo "No image";
                }
                echo "</td>";
                echo "<td class='sales-cell'>" . htmlspecialchars($row['product_name']) . "</td>";
                echo "<td class='sales-cell'>" . htmlspecialchars($row['quantity']) . "</td>";
                echo "<td class='sales-cell'>$" . number_format($row['price'], 2) . "</td>";
                echo "<td class='sales-cell'>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td class='sales-cell'>";
                echo "<button class='icon-btn' onclick='window.location.href=\"../models/edit_sale.php?id=" . 
                     htmlspecialchars($row['id']) . "\"'>✏️</button>";
                echo "<button class='icon-btn' onclick='window.location.href=\"../models/delete_sale.php?id=" . 
                     htmlspecialchars($row['id']) . "\"'>🗑️</button>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

<script>
// Add this to your existing script section
document.addEventListener('DOMContentLoaded', function() {
    const productSelect = document.getElementById('product_id');
    const priceInput = document.getElementById('price');
    const quantityInput = document.getElementById('quantity');
    const totalInput = document.getElementById('total');

    // Update price when product is selected
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const maxQuantity = selectedOption.getAttribute('data-quantity');
        
        priceInput.value = price || '';
        quantityInput.setAttribute('max', maxQuantity);
        calculateTotal();
    });

    // Calculate total when price or quantity changes
    [priceInput, quantityInput].forEach(input => {
        input.addEventListener('input', calculateTotal);
    });

    // Check quantity against available stock
    quantityInput.addEventListener('change', function() {
        const max = parseInt(this.getAttribute('max'));
        const value = parseInt(this.value);
        
        if (value > max) {
            alert('Not enough stock available. Maximum available: ' + max);
            this.value = max;
            calculateTotal();
        }
    });

    function calculateTotal() {
        const price = parseFloat(priceInput.value) || 0;
        const quantity = parseInt(quantityInput.value) || 0;
        totalInput.value = (price * quantity).toFixed(2);
    }
});
</script>

      <!-- REPORTS -->
      <div class="section" id="reports">
    <div class="reports-panel">
        <h2>Sales Reports</h2>
        <table class="report-table">
            <thead>
                <tr>
                    <th class="report-header">Date</th>
                    <th class="report-header">Product Name</th>
                    <th class="report-header">In-Stock</th>
                    <th class="report-header">Buy Price</th>
                    <th class="report-header">Sell Price</th>
                    <th class="report-header">Total Qty.</th>
                    <th class="report-header">Total Income</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Initialize totals
                $grand_total = 0;
                $total_profit = 0;

                $stmt = $user->getSales();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // Calculate totals
                    $row['total'] = ($row['sell_price'] - $row['buy_price']);
                    $row['profit'] = ($row['sell_price'] - $row['buy_price']);
                    $row['grand_total'] = $row['sell_price'] * $row['quantity'];
                    $grand_total += $row['grand_total'];
                    $total_profit += $row['profit'];
                    
                    echo "<tr>";
                    echo "<td class='report-cell'>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td class='report-cell'>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td class='report-cell'>" . htmlspecialchars($row['stock']) . "</td>";      
                    echo "<td class='report-cell'>$" . number_format($row['buy_price'], 2) . "</td>";
                    echo "<td class='report-cell'>$" . number_format($row['sell_price'], 2) . "</td>";
                    echo "<td class='report-cell'>" . htmlspecialchars($row['quantity']) . "</td>";
                    echo "<td class='report-cell'>$" . number_format($row['total'], 2) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="totals-row">
                    <td colspan="6" class="report-footer">Grand Total</td>
                    <td class="report-footer">$<?php echo number_format($grand_total, 2); ?></td>
                </tr>
                <tr class="totals-row">
                    <td colspan="6" class="report-footer">Total Profit</td>
                    <td class="report-footer">$<?php echo number_format($total_profit, 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

      <script>
            let isNavbarOpen = false;

            function toggleNavbar() {
                  const navbar = document.getElementById("navbar");
                  const sections = document.querySelectorAll('.section');
                  const menuBtn = document.getElementById("menu-btn");

                  navbar.classList.toggle("open");
                  sections.forEach(section => section.classList.toggle("shift"));

                  isNavbarOpen = !isNavbarOpen;

                  menuBtn.style.display = isNavbarOpen ? "none" : "block";
            }

            function showSection(sectionId) {
                  const sections = document.querySelectorAll('.section');
                  sections.forEach(section => section.classList.remove('active'));

                  document.getElementById(sectionId).classList.add('active');
            }

            function toggleAdminMenu() {
                  const dropdown = document.getElementById('dropdown-content');
                  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            }

            function logout() {
                  alert("Feature Unavailable...");
            }

            window.onclick = function (event) {
                  if (!event.target.matches('.admin-btn')) {
                        const dropdown = document.getElementById('dropdown-content');
                        if (dropdown.style.display === 'block') {
                              dropdown.style.display = 'none';
                        }
                  }
            }
      </script>

</body>

</html>
