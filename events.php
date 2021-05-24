<?php
include 'auth.php';
if (!checkAuth()) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <title>UnikArt - Tutti gli eventi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style/events.css">
  <script src="scripts/events.js" defer="true"></script>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="images/favicon.ico" />
</head>
<body>
<nav>
  <div id="logo">
    <img src="images/Logo_square.png">
  </div>
  <div id="links">
  <a href="index.php">Home</a>
    <a href="events.php">Eventi</a>
    <a href='explore.php'>Esplora</a>
    <?php
      require_once 'auth.php';
      if (checkAuth() == $admin) {
        echo "<a href='admin.php'>Admin</a>";
      }
    ?>
    <a href='logout.php'>Logout</a>
  </div>
</nav>
<div id="nav-back"></div>

<main>
  <section id="all-events">
    <div class="border-back"><h4>I tuoi eventi in evidenza</h4></div>
    <div class="highlights">

    </div>

    <div class="border-back"><h4>Tutti gli eventi</h4></div>
    <div class="filter">
        <div class="check">
            <div><input type="checkbox"></div>
            <div><label for="check-box">Mostra solo dai Direttori Artistici che segui</label></div>
        </div>
        <div class="number">
            <div><label for="number">Risultati</label></div>
            <div><input type="number" step="10" min="10" max="100" value=""></div>
        </div>
        <div class="submit">
            <input type="submit">
        </div>
    </div>
    <div class="event-list">
    </div>
  </section>
  <section id="main-event">
  </section>
  <section id="adv">
  </section>
</main>
</body>

</html>