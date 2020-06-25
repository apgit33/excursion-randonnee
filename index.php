<?php
session_start();
require_once 'bdd.php';
require_once 'function.php';

$user = (isset($_POST['user']))?$_POST['user']:'';
$pwd = (isset($_POST['password']))?$_POST['password']:'';

if(isset($_GET['action'])=='logout') {
    header('Location: index.php');
    $_SESSION['user'] = '';
    $_SESSION['admin'] = false;
    exit;
}


if ($user !='' && $pwd != '') {
    $query = "SELECT * FROM `nc_admin` WHERE a_user = ? AND password = ?";

    		
    $reponse = doIt2($query,$user,$pwd);

    while ($donnees = $reponse->fetch()) {
        $_SESSION['user'] = $donnees['a_user'];
        $_SESSION['admin'] = true;
    }
}



include 'header.php';

?>

<?php if(isset($_SESSION['admin']) && $_SESSION['admin']===true){
?>
    <div class='column is-5'>
        <div class='card'>
            <div class='card-image'>
                <figure class='image is-4by3'>
                    <img src='https://bulma.io/images/placeholders/1280x960.png' alt='Placeholder image'>
                </figure>
            </div>
            <div class='card-content'>
                <div class='media'>
                    <div class='media-left'>
                        <figure class='image is-48x48'>
                            <img src='https://bulma.io/images/placeholders/96x96.png' alt='Placeholder image'>
                        </figure>
                    </div>
                    <div class='media-content'>
                        <p class='title is-4'><?=$_SESSION['user']?></p>
                    </div>
                </div>

                <div class='content'>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Phasellus nec iaculis mauris.
                </div>
            </div>
        </div>
    </div>
<?php
}else{
    ?>
    <div class='columns'>
        <div class='column'>
            <h2 class="title is-2 is-centered">Connection</h2>
            <form action='' method='POST' id='form-login'>
                
                <div class='field'>
                    <label for='user'>Login :</label>
                    <div class='control'>
                        <input class='input' type='text' name='user'  placeholder='Enter your user name' id='user' required >
                    </div>
                </div>

                <div class='field'>
                    <label for='password'>Password :</label>
                    <div class='control'>
                        <input class="input" type='password' name='password' placeholder='Enter your password' id='password' required>
                    </div>
                </div>

                <div class='field'>
                    <div class='control'>
                        <input type='submit' class='input is-primary button' value='Connection'>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
}


include 'footer.php';



