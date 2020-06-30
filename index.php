<?php
session_start();
require_once 'bdd.php';


if(isset($_GET['action'])=='logout') {
    header('Location: index.php');
    session_unset();
    exit;
}

//Si l'utilisateur est déjà connecté, on l'envoie sur son profil
if(isset($_SESSION['login']) && $_SESSION['login']===true){ 
    header('Location: dashboard.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel="icon" type="image/png" href="favicon.png" />
        <title>Natural Coach</title>
        <link rel='stylesheet' href='https://cdn.materialdesignicons.com/5.3.45/css/materialdesignicons.min.css'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css'>
        <link rel='stylesheet' href='./css/default.css'>
    </head>
    <body>
        <main> 
            <div class="h-100 d-flex d-centered">
            <div class='container'>
        <div class='columns'>
        <div class='column is-4 mr-auto form-login'>
            <h2 class="title is-2 has-text-centered">Login</h2>
            <form action='' method='POST' id='form-login'>
                <div class='field'>
                    <label for='email' class='label'>Email :</label>
                    <div class='control'>
                        <input class='input' type='email' name='email'  placeholder='Enter your email' id='email' required >
                    </div>
                </div>
                <div class='field'>
                    <label for='password' class='label'>Password :</label>
                    <div class='control'>
                        <input class="input" type='password' name='password' placeholder='Enter your password' id='password' required>
                    </div>
                </div>
                <div class='field'>
                    <div class='control'>
                        <input type='submit' class='input is-success button is-centered' value='Login'>
                    </div>
                </div>
            </form>
            <div id='checklogin' class='verif'></div>
        </div>
    </div>
                 </div>  
            </div>   
        </main>
        <script src='./src/login.js'></script>
    </body>
</html>




