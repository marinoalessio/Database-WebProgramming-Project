<?php include 'auth.php';?>
 
 <!DOCTYPE html>
 <head>
    <meta charset="utf-8">
    <title>UnikArt - Home</title>
	  <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style/index.css">
    <script src="scripts/index.js" defer="true"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
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
        <?php
          if (checkAuth()) {
            echo "<a href='explore.php'>Esplora</a>";
            if(checkAuth() == $admin) echo "<a href='admin.php'>Admin</a>";
            echo "<a href='logout.php'>Logout</a>";
          }else{
            echo "<a href='login.php'>Accedi</a>";
          }
        ?>
      </div>
    </nav>
    <header>
      <h1>Unik Art</h1>
      <p>Una trasformazione che celebra l'arte</p>
      <div class="overlay"></div>
    </header>
   
    <section>
      <div id="main">
        <img src="images/Logo_intero.png" >
         <p>
          Unik Art è un portale online di mostre digitali, per raccontare i grandi capolavori in una nuova chiave di lettura. <br>
          Sostiene il settore dei beni culturali nella sua trasformazione digitale per accogliere i cambiamenti del mondo digitale e incoraggiare collaborazioni che promuovano l'innovazione.
          La nostra esperienza ed originalità unite alla collaborazione con professionisti qualificati e creativi ci permettono di organizzare mostre puntando sulla valorizzazione del passato ma conservando sempre soluzioni efficienti e di impatto.
          Per raggiungere il nostro obiettivo sono necessari ingredienti semplici, ma sempre efficaci: l'esperienza, la competenza, la creatività, l’originalità e tanto entusiasmo.
        </p>
      </div>
      <div id="events-container">
        <h1>Prossimi Eventi</h1>
    </section>
    <div id="load-more">
      <a href="events.php" class="button">Visualizza tutti</a>
    </div>

    <footer>
      <p>Alessio Marino O46002088</br>
        Università degli Studi di Catania 2021</p>
    </footer>
  </body>
  </html>