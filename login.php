<?php
include 'auth.php';
if (checkAuth()) {
    header('Location: explore.php');
    exit;
}

if (!empty($_POST["username"]) && !empty($_POST["password"])){
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    //if input is email use it else username
    $searchField = filter_var($username, FILTER_VALIDATE_EMAIL) ? "email" : "username";
    
    $query = "SELECT id, username, password FROM users WHERE $searchField = '$username'";
    
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));;
    if (mysqli_num_rows($res) > 0) {

        $entry = mysqli_fetch_assoc($res);
        $id = $entry['id']; //USER'S!!!
        if (password_verify($_POST['password'], $entry['password'])) {

            //set cookies if checkbox is checked
            if(!empty($_POST['remember'])){
                $token = random_bytes(12);
                $hash = password_hash($token, PASSWORD_BCRYPT);
                $expiration = strtotime("+1 week");

                $query = "INSERT INTO cookies(user, hash, expires) VALUES ($id, '$hash', $expiration)";
                $res = mysqli_query($conn, $query) or die(mysqli_error($conn));
                setcookie("_user_id", $id, $expiration);
                setcookie("_cookie_id", mysqli_insert_id($conn), $expiration);
                setcookie("_token", $token, $expiration);
            }

            // start session
            $_SESSION["_user_id"] = $id;
            header("Location: explore.php");
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;
        }
    }
    $error = "Username e/o password errati.";
}
else if (isset($_POST["username"]) || isset($_POST["password"])) $error = "Inserisci username e password.";

?>

<html>
    <head>
        <link rel='stylesheet' href='style/login.css'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/favicon.ico">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">

        <title>UnikArt - Login</title>
    </head>
    <body>
        <div id="container">

            <div class="main-left">
                <h1>Benvenuto</h1>
                <p>Entra nella Community di appassionati d'arte per non perderti i nostri eventi</p>
            </div>

            <div class="main-right">
                <img src="images/Logo_intero.png" >
                <?php
                    if (isset($error)) {
                        echo "<span class='error'>$error</span>";
                    }
                    
                ?>
                <form name='login' method='post'>
                    <div class="username">
                        <div><label for='username'>Nome utente o email</label></div>
                        <div><input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>></div>
                    </div>
                    <div class="password">
                        <div><label for='password'>Password</label></div>
                        <div><input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>></div>
                    </div>
                    <div class="remember">
                        <span><input type='checkbox' name='remember' value="1" <?php if(isset($_POST["remember"])){echo $_POST["remember"] ? "checked" : "";} ?>></span>
                        <span><label for='remember'>Ricorda l'accesso</label></span>
                    </div>

                    <div class="submit">
                        <input type='submit' value="Accedi">
                    </div>
                </form>
                <div class="register">
                    <span>Non hai un account? </span><span><a href="signup.php">Registrati</a></span>
                </div>
            </div>

        </div>
    </body>
</html>