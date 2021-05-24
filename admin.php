<?php
include 'auth.php';
if (!(checkAuth() == $admin)) {
    exit;
}

// per il direttore artistico
if(isset($_POST['dir_cf']) && isset($_POST['dir_name']) && isset($_POST['dir_surname']) 
    && isset($_POST['dir_qualification']) && isset($_FILES["file_dir"])){
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

        $cf = mysqli_real_escape_string($conn, $_POST["dir_cf"]);
        $name = mysqli_real_escape_string($conn, $_POST["dir_name"]);
        $surname = mysqli_real_escape_string($conn, $_POST['dir_surname']);
        $qualification = mysqli_real_escape_string($conn, $_POST['dir_qualification']);
        $response = array();

        $query = "SELECT * FROM DIRECTORS WHERE cf = '$cf'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        if (mysqli_num_rows($res) > 0){
            $response[] = "Hai già caricato questo Direttore Artistico";
            mysqli_close($conn);
        }else{ 
            $target_dir = "images/propic/";
            $target_file = $target_dir . basename($_FILES["file_dir"]["name"]);
            $target_file = explode(".", $target_file);
            $target_file[0] .= "_" . $name . "_" . $surname;
            $target_file = implode(".", $target_file);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
            if ($_FILES["file_dir"]["size"] > 5000000) {
                $response[] = "Il tuo file è più grande di 5MB";
                $uploadOk = 0;
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $response[] = "I formati accettati sono jpg, png, jpeg e gif";
                $uploadOk = 0;
            }
            if (!($uploadOk == 0)){
                if (move_uploaded_file($_FILES["file_dir"]["tmp_name"], $target_file)) {
                    $query = "INSERT INTO DIRECTORS(cf, name, surname, qualification, img) VALUES('$cf', '$name', '$surname', '$qualification', '$target_file')";
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if($res) $response[] = "Il Direttore Artistico è stato inserito correttamente";
                    else $response[] = "Si è verificato un errore nel caricamento sul database";
                    header("admin.php");
                }else $response[] = "Errore nel caricamento del file";
            }
        }
    }
}

// per la guida
if(isset($_POST['guide_cf']) && isset($_POST['guide_name']) && isset($_POST['guide_surname']) 
    && isset($_POST['guide_qualification']) && isset($_FILES["file_guide"])){
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

        $cf = mysqli_real_escape_string($conn, $_POST["guide_cf"]);
        $name = mysqli_real_escape_string($conn, $_POST["guide_name"]);
        $surname = mysqli_real_escape_string($conn, $_POST['guide_surname']);
        $qualification = mysqli_real_escape_string($conn, $_POST['guide_qualification']);
        $response = array();

        $query = "SELECT * FROM GUIDES WHERE cf = '$cf'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        if (mysqli_num_rows($res) > 0){
            $response[] = "Hai già caricato questa guida";
            mysqli_close($conn);
        }else{
            $target_dir = "images/propic/";
            $target_file = $target_dir . basename($_FILES["file_guide"]["name"]);
            $target_file = explode(".", $target_file);
            $target_file[0] .= "_" . $name . "_" . $surname;
            $target_file = implode(".", $target_file);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
            if ($_FILES["file_guide"]["size"] > 5000000) {
                $response[] = "Il tuo file è più grande di 5MB";
                $uploadOk = 0;
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $response[] = "I formati accettati sono jpg, png, jpeg e gif";
                $uploadOk = 0;
            }
            if (!($uploadOk == 0)){
                if (move_uploaded_file($_FILES["file_guide"]["tmp_name"], $target_file)) {
                    $query = "INSERT INTO GUIDES(cf, name, surname, qualification, img) VALUES('$cf', '$name', '$surname', '$qualification', '$target_file')";
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if($res) $response[] = "La guida è stata inserita correttamente";
                    else $response[] = "Si è verificato un errore nel caricamento sul database";
                    header("admin.php");
                }else $response[] = "Errore nel caricamento del file";
            }
        }
    }
}

// per l'evento
if(isset($_POST['title']) && isset($_POST['date-and-time']) && isset($_POST['time']) 
    && isset($_FILES['event_cover']) && isset($_POST["dir_choice"]) && isset($_POST["guide_choice"])){
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

        $title = mysqli_real_escape_string($conn, $_POST["title"]);

        $date_and_time = mysqli_real_escape_string($conn, $_POST["date-and-time"]);
        $date_and_time = explode('T', $date_and_time);
        $date_and_time = implode(" ", $date_and_time) . ":00";

        $duration = mysqli_real_escape_string($conn, $_POST['time']);
        $duration = explode(":", $duration);
        $duration = (int)$duration[0]*60 + (int)$duration[1];

        $tags = mysqli_real_escape_string($conn, $_POST['tags']);

        $dir_choice = mysqli_real_escape_string($conn, $_POST['dir_choice']);
        $guide_choice = mysqli_real_escape_string($conn, $_POST['guide_choice']);
        
        $responseEvent = array();

        $query = "SELECT * FROM EVENTS WHERE title = '$title' AND date_and_time = '$date_and_time'";
        $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

        if (mysqli_num_rows($res) > 0){
            $responseEvent[] = "Hai già caricato questo evento";
            mysqli_close($conn);
        }else{
            // if everything is ok, adjust name of target file 
            $target_dir = "images/covers/";
            $target_file = $target_dir . basename($_FILES["event_cover"]["name"]);
            $target_file = explode(".", $target_file);
            $target_file[0] .= "_" . $title;
            $target_file = implode(".", $target_file);
            $target_file = str_replace(array( '\'', '"', ',' , ';', ':', '<', '>' ), ' ', $target_file);
            $error = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
            if ($_FILES["event_cover"]["size"] > 10000000) {
                $responseEvent[] = "Il tuo file è più grande di 10MB";
                $error = 0;
            }
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
                $responseEvent[] = "I formati accettati sono jpg, png, jpeg e gif";
                $error = 0;
            }
            if (!($error == 0)){ //upload cover image
                if (move_uploaded_file($_FILES["event_cover"]["tmp_name"], $target_file)) {
                    $query = "INSERT INTO EVENTS(title, date_and_time, duration, tags, cover, director, guide) 
                    VALUES('$title', '$date_and_time', $duration, '$tags', '$target_file', '$dir_choice', '$guide_choice')";
                    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                    if($res) $responseEvent[] = "L'evento è stato inserito correttamente";
                    else $responseEvent[] = "Si è verificato un errore nel caricamento sul database";
                    header("admin.php");
                }else $responseEvent[] = "Errore nel caricamento del file";
            }
        }
    }
}

?>

<!DOCTYPE html>

<head>
  <meta charset="utf-8">
  <title>UnikArt - Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style/admin.css">
  <script src="scripts/admin.js" defer="true"></script>
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
    <a href="eventi.php">Eventi</a>
    <a href='explore.php'>Esplora</a>
    <a href="admin.php">Admin</a>
    <a href='logout.php'>Logout</a>
  </div>
</nav>
<div id="nav-back"></div>

<main>
    <div class="response">
        <?php if(!empty($response)) echo implode(", ",$response); ?>
    </div>
    <div id="director">
        <h1>Inserisci Direttore Artistico</h1>
        <form name='director' method='post' enctype="multipart/form-data" autocomplete="off">
            <div class="cf">
                <label for="cf">Codice Fiscale</label>  
                <input type='text' name='dir_cf'>
            </div>
            <div class="name">
                <label for="name">Nome</label>  
                <input type='text' name='dir_name'>
            </div>
            <div class="surname">
                <label for="surname">Cognome</label>
                <input type='text' name='dir_surname'>
            </div>
            <div class="qualification">
                <label for="qualification">Titolo di studio</label>
                <input type='text' name='dir_qualification'>
            </div>

            <div class="fileupload">
                <label for='propic'>Immagine profilo</label>
                <input type="file" name="file_dir" id="file_dir">
            </div>

            <div class="submit">
                <input type='submit' value="Carica">
            </div>

        </form>
    </div>

    <div id="guides">
    <h1>Inserisci Guida</h1>
        <form name='guide' method='post' enctype="multipart/form-data" autocomplete="off">
            <div class="cf">
                <label for="cf">Codice Fiscale</label>  
                <input type='text' name='guide_cf'>
            </div>
            <div class="name">
                <label for="name">Nome</label>  
                <input type='text' name='guide_name'>
            </div>
            <div class="surname">
                <label for="surname">Cognome</label>
                <input type='text' name='guide_surname'>
            </div>
            <div class="qualification">
                <label for="qualification">Titolo di studio</label>
                <input type='text' name='guide_qualification'>
            </div>

            <div class="fileupload">
                <label for='propic'>Immagine profilo</label>
                <input type="file" name="file_guide" id="file_guide">
            </div>

            <div class="submit">
                <input type='submit' name="submit" value="Carica">
            </div>

        </form>
    </div>
    <div class="response">
        <?php if(!empty($responseEvent)) echo implode(", ",$responseEvent); ?>
    </div>
    <div id="events">
        <h3>Carica Evento</h3>
        <form name='event' method='post' enctype="multipart/form-data" autocomplete="off">
            <div class="left">
                <div class="title">
                    <label for='title'>Titolo</label>
                    <input type="text" name="title">
                </div>
                <div class="date-and-time">
                    <label for="date-and-time">Data e ora</label>
                    <input type="datetime-local" name="date-and-time"
                    min="2021-05-25T00:00" max="2030-12-31T23:59">
                </div>
                <div class="duration">
                    <label for="duration">Durata</label>
                    <input type="time" name="time" required>
                </div>
                <div class="fileupload">
                    <label for='event-cover'>Copertina evento</label>
                    <input type="file" name="event_cover">
                </div>
                <div class="tags">
                    <div><label for='event-cover'>Tags</label></div>
                    <textarea name="tags" placeholder="Digital Art, Romanticismo, Architettura..." 
                    cols="50" rows="5" maxlength="750"></textarea>
                </div>
            </div>

            <div class="right">
                <div><label for='director'>Direttore Artistico</label></div>
                <div class="director">
                    <div class="select">
                        <select class="choice" name="dir_choice">
                            <option value="none" selected hidden></option>
                            <?php
                                $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
                                $query_dir = "SELECT cf, name, surname FROM DIRECTORS";
                                $res = mysqli_query($conn, $query_dir) or die(mysqli_error($conn));
                                while($entry = mysqli_fetch_assoc($res)) {
                                    echo "<option value=".$entry['cf'].">".$entry['name']." ".$entry['surname']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div><label for='guide'>Guida</label></div>
                <div class="guide">
                    <div class="select">
                        <select class="choice" name="guide_choice">
                            <option value="none" selected hidden></option>
                            <?php
                                $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
                                $query_dir = "SELECT cf, name, surname FROM GUIDES";
                                $res = mysqli_query($conn, $query_dir) or die(mysqli_error($conn));
                                while($entry = mysqli_fetch_assoc($res)) {
                                    echo "<option value=".$entry['cf'].">".$entry['name']." ".$entry['surname']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="submit">
                    <input type='submit' name="submit" value="Carica">
                </div>
            </div>

        </form>
    </div>

</main>
</body>

</html>