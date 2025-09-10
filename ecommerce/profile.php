<?php
session_start();
include 'db.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch address if exists
$stmt = $conn->prepare("SELECT * FROM address WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$address = $stmt->get_result()->fetch_assoc();

// Handle Save/Update Address
if (isset($_POST['save_address'])) {
    $house_no    = $_POST['house_no'];
    $street      = $_POST['street'];
    $town        = $_POST['town'];
    $municipality= $_POST['municipality'];
    $province    = $_POST['province'];
    $country     = $_POST['country'];
    $postal_code = $_POST['postal_code'];

    if ($address) {
        $stmt = $conn->prepare("UPDATE address 
            SET house_no=?, street=?, town=?, municipality=?, province=?, country=?, postal_code=? 
            WHERE user_id=?");
        $stmt->bind_param("sssssssi", $house_no, $street, $town, $municipality, $province, $country, $postal_code, $user_id);
        $stmt->execute();
        $_SESSION['message'] = "Address updated successfully!";
    } else {
        $stmt = $conn->prepare("INSERT INTO address 
            (user_id, house_no, street, town, municipality, province, country, postal_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $user_id, $house_no, $street, $town, $municipality, $province, $country, $postal_code);
        $stmt->execute();
        $_SESSION['message'] = "Address added successfully!";
    }

    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Profile - Manage Address</title>
  <link rel="stylesheet" href="profile.css">
  <link rel="stylesheet" href="header.css">
</head>
<body>
  <?php include 'header.php'; ?> <!-- ✅ header added -->

  <div class="container">
    <h1>Your Profile</h1>
    <h2>Manage Address</h2>

    <?php 
    if (isset($_SESSION['message'])) {
        echo "<p style='color:lightgreen;'>".$_SESSION['message']."</p>";
        unset($_SESSION['message']);
    }
    ?>

    <form method="POST">
      <input type="text" name="house_no" placeholder="House No." value="<?= htmlspecialchars($address['house_no'] ?? '') ?>" required>
      <input type="text" name="street" placeholder="Street" value="<?= htmlspecialchars($address['street'] ?? '') ?>" required>
      <input type="text" name="town" placeholder="Barangay" value="<?= htmlspecialchars($address['town'] ?? '') ?>" required>
      <input type="text" name="municipality" placeholder="Municipality/City" value="<?= htmlspecialchars($address['municipality'] ?? '') ?>" required>
      <input type="text" name="province" placeholder="Province" value="<?= htmlspecialchars($address['province'] ?? '') ?>" required>
      <input type="text" name="country" placeholder="Country" value="<?= htmlspecialchars($address['country'] ?? '') ?>" required>
      <input type="text" name="postal_code" placeholder="Postal Code" value="<?= htmlspecialchars($address['postal_code'] ?? '') ?>" required>
      
      <button type="submit" name="save_address"><?= $address ? "Update Address" : "Save Address"; ?></button>
    </form>
  </div>
</body>
</html>
