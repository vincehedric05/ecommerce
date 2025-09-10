<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
  <div class="logo">
    <h2><a href="index.php">SEEORA</a></h2>
  </div>

  <div class="search-bar">
    <form action="index.php" method="get">
      <input type="text" name="search" placeholder="Search products...">
      <button type="submit">Search</button>
    </form>
  </div>

  <nav class="nav-links">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="profile.php">Profile</a>
      <a href="cart.php">Cart</a>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Profile</a>
      <a href="login.php">Cart</a>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>
</header>
