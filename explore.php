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
  <title>UnikArt - Esplora</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style/explore.css">
  <script src="scripts/explore.js" defer="true"></script>
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
  <section id="profile">
    <div class="info-block">
      
      <div class="img-container">
      </div>

      <div class="names">
      </div>

      <div class="activity">
        <div class="nfollowing">
        </div>
        <div class="nreviews">
        </div>
      </div>

    </div>
  </section>

  <section id="reviews">
    <div id="create-post">
      <form name='write-review' method='post' enctype="multipart/form-data" autocomplete="off">
        <div class="choose">
          <input type="button" name="searchArtwork" value="Cerca l'opera">
        </div>
        <div class="write">
          <textarea name="review" placeholder="Scrivi una recensione..." cols="70" rows="50" maxlength="750"></textarea>
        </div>
        <div class="submit">
          <input type='submit' value="Pubblica">
          <div class="rating">
            <select name="rating">
              <option value="0" selected hidden></option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
            </select>
            <label for="star">&#9733</label>
          </div>
        </div>
      </form>
    
    
    </div>
  </section>

  <section id="subscriptions">
    <h4>Le tue iscrizioni</h4>
    <div class="user-subscriptions">

    </div>  
    <h4>Altri Direttori Artistici</h4>
    <div class="other-directors">
    
    </div> 
  </section>

  

</main>
</body>

</html>