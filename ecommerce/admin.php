<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel - Manage Products</title>
  <style>
    img { vertical-align: middle; }
    form.inline { display: inline; margin-left: 10px; }
  </style>
</head>
<body>
  <h1>Admin Panel</h1>

  <form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required>
    <textarea name="description" placeholder="Product Description" required></textarea>
    <input type="number" name="price" step="any" placeholder="Price" required>
    
    <select name="category" required>
      <option value="">-- Select Category --</option>
      <option value="Shades">Shades</option>
      <option value="Clear">Clear</option>
    </select>
    
    <input type="file" name="image" accept="image/*" required>
    <button type="submit" name="add">Add Product</button>
  </form>

  <h2>Current Products</h2>
  <ul>
    <?php
    $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
    while($row = $result->fetch_assoc()) {
      $img = $row['image'] ? "img/".$row['image'] : "no-image.png";
      $featured = $row['featured'] ? "⭐ Featured" : "";
      echo "
        <li>
          <img src='$img' width='50'> 
          {$row['name']} - ₱ {$row['price']} ({$row['category']}) $featured

          <!-- Remove button -->
          <form method='POST' class='inline'>
            <input type='hidden' name='delete_id' value='{$row['product_id']}'>
            <button type='submit' name='delete'>Remove</button>
          </form>

          <!-- Toggle featured button -->
          <form method='POST' class='inline'>
            <input type='hidden' name='feature_id' value='{$row['product_id']}'>
            <button type='submit' name='toggle_feature'>
              ".($row['featured'] ? "Unfeature" : "Feature")."
            </button>
          </form>
        </li>
      ";
    }
    ?>
  </ul>

  <?php
  if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $imageName = time() . "_" . basename($_FILES['image']['name']);
    $tmpName   = $_FILES['image']['tmp_name'];
    $targetDir = "img/";
    $targetFile = $targetDir . $imageName;

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (move_uploaded_file($tmpName, $targetFile)) {
      $stmt = $conn->prepare("INSERT INTO products (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
      $stmt->bind_param("ssdss", $name, $desc, $price, $category, $imageName);
      $stmt->execute();
      echo "<p>Product added successfully!</p>";
      echo "<meta http-equiv='refresh' content='0'>"; 
    } else {
      echo "<p>Image upload failed.</p>";
    }
  }

  if (isset($_POST['delete'])) {
    $id = intval($_POST['delete_id']); // safe
    $conn->query("DELETE FROM products WHERE product_id=$id");
    echo "<p>Product removed!</p>";
    echo "<meta http-equiv='refresh' content='0'>"; 
  }

  if (isset($_POST['toggle_feature'])) {
    $id = intval($_POST['feature_id']); // safe
    $conn->query("UPDATE products SET featured = IF(featured=1, 0, 1) WHERE product_id=$id");
    echo "<meta http-equiv='refresh' content='0'>"; 
  }
  ?>
</body>
</html>
