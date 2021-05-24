<?php
    require_once 'auth.php';
    if (checkAuth()) {
        header("Location: explore.php");
        exit;
    }   

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["name"]) && 
        !empty($_POST["surname"]) && !empty($_POST["confirm_password"]) && !empty($_POST["allow"])){
        $error = array();
        $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));

        // username
        if(!preg_match('/^[A-Za-z0-9à-ù_\-\.]{1,15}$/', $_POST['username'])) {
            $error[] = "Username non valido";
        } else {
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $query = "SELECT username FROM users WHERE username = '$username'";
            $res = mysqli_query($conn, $query);
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Username non disponibile";
            }
        }
        
        //password
        if(!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,20}$/', $_POST['password'])){
            $error[] = "Caratteri password insufficienti";
        }
        
        //confirm password
        if (strcmp($_POST["password"], $_POST["confirm_password"]) != 0) {
            $error[] = "Le password non coincidono";
        }
        
        // email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));
            $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");
            if (mysqli_num_rows($res) > 0) {
                $error[] = "Email già utilizzata";
            }
        }

        //if everything is ok, proceed
        if (count($error) == 0) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $surname = mysqli_real_escape_string($conn, $_POST['surname']);

            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $password = password_hash($password, PASSWORD_BCRYPT);  // save hashed password

            $avatar = 'images/avatar/'.$_POST['avatar'].'.png';

            $query = "INSERT INTO users(username, password, name, surname, email, avatar) 
            VALUES('$username', '$password', '$name', '$surname', '$email', '$avatar')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION["_user_id"] = mysqli_insert_id($conn);
                mysqli_close($conn);
                header("Location: explore.php");
                exit;
            } else {
                $error[] = "Errore di connessione al Database";
            }
        }
        mysqli_close($conn);
    }
?>

<html>
<head>
    <link rel='stylesheet' href='style/signup.css'>
    <script src='scripts/signup.js' defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="favicon.png">
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <title>Iscriviti</title>
</head>

<body>
    <section class="register">
        <h1>Siamo felici di averti con noi</h1>
        <?php 
            if(isset($error)){
                $res_error = implode("\n", $error);
                echo '<p class="error">Errore: '.$res_error .'</p>';
            };
        ?>
        <form name='signup' method='post' enctype="multipart/form-data" autocomplete="off">
            <div class="names">
                <div class="name">
                    <div><label for='name'>Nome</label></div>
                    <div>
                        <input type='text' name='name' <?php if(isset($_POST["name"])){echo "value=" .$_POST["name"];}?>>
                        <p class="hidden">Nome non valido</p>
                    </div>
                    
                </div>
                <div class="surname">
                    <div><label for='surname'>Cognome</label></div>
                    <div><input type='text' name='surname' <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];} ?>>
                        <p class="hidden">Cognome non valido</p>
                    </div>
                </div>
            </div>
            <div class="username">
                <div><label for='username'>Username</label></div>
                <div>
                    <input type='text' name='username' <?php if(isset($_POST["username"])){echo "value=".$_POST["username"];} ?>>
                    <p class="hidden"></p>
                </div>
            </div>
            <div class="email">
                <div><label for='email'>Email</label></div>
                <div>
                    <input type='text' name='email' <?php if(isset($_POST["email"])){echo "value=" .$_POST["email"];}?>>
                    <p class="hidden"></p>
                </div>
            </div>
            <div class="password">
                <div><label for='password'>Password</label></div>
                <div>
                    <input type='password' name='password' <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];} ?>>
                    <p class="hidden"></p>
                </div>
                
            </div>
            <div class="confirm_password">
                <div><label for='confirm_password'>Conferma Password</label></div>
                <div>
                    <input type='password' name='confirm_password' <?php if(isset($_POST["confirm_password"])){echo "value=" .$_POST["confirm_password"];} ?>>
                    <p class="hidden"></p>
                </div>
            </div>
            <div class="avatar">
                <label for="avatar">Scegli il tuo avatar:</label>
                <select name="avatar" <?php if(isset($_POST["avatar"])){echo "value=" .$_POST["avatar"];} ?>>
                    <option value="none" selected hidden></option>
                    <option value="frida">Frida</option>
                    <option value="picasso">Picasso</option>
                    <option value="leonardo">Leonardo</option>
                    <option value="vangogh">Van Gogh</option>
                    <option value="dali">Dalì</option>
                </select>
            </div>
            <div class="allow">
                <span><input type='checkbox' name='allow' value="1" <?php if(isset($_POST["allow"])){echo
                        $_POST["allow"] ? "checked" : "" ;} ?>></span>
                <span><label for='allow'>Ho letto e accetto i Termini di Servizio</label></span>
            </div>
            <div class="submit">
                <input type='submit' value="Registrati" id="submit" disabled>
            </div>
        </form>
        <span class="login">Hai gia un account? <a href="mylogin.php">Accedi</a></span>
    </section>
</body>

</html>