<?php 
include 'db.php';

if (!isset($_GET['id'])) {
    echo "Product not found.";
    exit;
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE product_id = $id");
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found.";
    exit;
}

$img = $product['image'] ? "img/" . $product['image'] : "no-image.png";
$desc = $product['description'] ?? "No description available.";
$category = $product['category'] ?? "Uncategorized";
?>

<!DOCTYPE html>
<html>
<head>
  <title><?php echo $product['name']; ?> - Product Page</title>
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="product.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <div class="product-page">
    <div class="product-image">
      <img src="<?php echo $img; ?>" alt="<?php echo $product['name']; ?>">
    </div>
    <div class="product-info">
      <h2><?php echo $product['name']; ?></h2>
      <p><strong>Price:</strong> ₱<?php echo $product['price']; ?></p>
      <p><strong>Category:</strong> <?php echo $category; ?></p>
      <p><strong>Description:</strong> <?php echo $desc; ?></p>

      <div class="color-select">
        <label for="frame_color">Choose Frame Color:</label>
        <select id="frame_color" name="frame_color">
          <option value="Red">Red</option>
          <option value="Blue">Blue</option>
          <option value="Black">Black</option>
          <option value="White">White</option>
        </select>
      </div>

      <button class="add-to-cart">Add to Cart</button>
    </div>
  </div>
</body>
</html>
