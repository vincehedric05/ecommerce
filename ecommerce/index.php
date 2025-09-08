<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Shop</title>
  <link rel="stylesheet" href="index.css">
  <link rel="stylesheet" href="header.css">
</head>
<body>
  <?php include 'header.php'; ?>

  <h1>Welcome to SEEORA</h1>

  <h2>Featured Products</h2>
  <div class="products featured">
    <?php
    $featuredQuery = "SELECT * FROM products WHERE featured = 1";

    if (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $featuredQuery .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
    }

    $featuredResult = $conn->query($featuredQuery);

    if ($featuredResult->num_rows > 0) {
        while($row = $featuredResult->fetch_assoc()) {
          $img = $row['image'] ? "img/".$row['image'] : "no-image.png";
          echo "
            <div class='product featured-product' onclick=\"location.href='product.php?id={$row['product_id']}'\">
              <img src='$img' alt='{$row['name']}'>
              <h3>{$row['name']}</h3>
              <p>Price: ₱ {$row['price']}</p>
            </div>
          ";
        }
    } else {
        echo "<p>No featured products found.</p>";
    }
    ?>
  </div>

  <h2>All Products</h2>
  <div class="products">
    <?php
    $query = "SELECT * FROM products WHERE featured = 0";

    if (isset($_GET['search'])) {
        $search = $conn->real_escape_string($_GET['search']);
        $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
    }

    if (isset($_GET['cat'])) {
        $cat = $conn->real_escape_string($_GET['cat']);
        $query .= (strpos($query, "WHERE") !== false ? " AND" : " WHERE") . " category='$cat'";
    }

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $img = $row['image'] ? "img/".$row['image'] : "no-image.png";
          echo "
            <div class='product' onclick=\"location.href='product.php?id={$row['product_id']}'\">
              <img src='$img' alt='{$row['name']}'>
              <h3>{$row['name']}</h3>
              <p>Price: ₱ {$row['price']}</p>
            </div>
          ";
        }
    } else {
        echo "<p>No products found.</p>";
    }
    ?>
  </div>
</body>
</html>
