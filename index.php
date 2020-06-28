<?php
session_start();
require_once 'bdd.php';


if(isset($_GET['action'])=='logout') {
    header('Location: index.php');
    $_SESSION['user_firstname'] = '';
    $_SESSION['user_lastname'] = '';
    $_SESSION['login'] = false;
    $_SESSION['admin'] = false;
    $_SESSION['id'] = '';
    exit;
}
//Si l'utilisateur est déjà connecté, on l'envoie sur son profil
if(isset($_SESSION['login']) && $_SESSION['login']===true){ 
    header('Location: dashboard.php');
    exit;
}
include 'header.php';

?>

    <div class='columns'>
        <div class='column'>
            <h2 class="title is-2 is-centered">Connection</h2>
            <form action='' method='POST' id='form-login'>
                <div class='field'>
                    <label for='email'>Login :</label>
                    <div class='control'>
                        <input class='input' type='email' name='email'  placeholder='Enter your email' id='email'  >
                    </div>
                </div>
                <div class='field'>
                    <label for='password'>Password :</label>
                    <div class='control'>
                        <input class="input" type='password' name='password' placeholder='Enter your password' id='password' >
                    </div>
                </div>
                <div class='field'>
                    <div class='control'>
                        <input type='submit' class='input is-success button' value='Connection'>
                    </div>
                </div>
            </form>
            <ul id='verif'></ul>
        </div>
    </div>
                 </div>       
        </main>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
        <script src='./src/login.js'></script>
    </body>
</html>




