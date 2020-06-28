<?php
session_start();
require_once 'bdd.php';

include 'header.php';
?>

<?php if(isset($_SESSION['login']) && $_SESSION['login']===true){

if(isset($_SESSION['admin']) && $_SESSION['admin']===true){
    $query ="SELECT e_nom FROM nc_excursion WHERE e_randonneurs_max - (SELECT count(*) FROM nc_booking WHERE b_e_id = e_id)>0";
?>
        <div class="card card-admin">
            <header class="card-header">
                <p class="card-header-title">Excursions</p>
            </header>
            <div class="card-content">
                <div class="content">
                    Total d'excursions : <?=executeSQL("SELECT count(`e_id`) FROM `nc_excursion`",array())->fetch()[0];?>
                </div>   
                <div class="content">
                    Excursions libre : <?=executeSQL("SELECT count(e_id) FROM nc_excursion WHERE e_randonneurs_max - (SELECT count(*) FROM nc_booking WHERE b_e_id = e_id)>0",array())->fetch()[0];?>
                </div>
            </div>
        </div>
        <div class="card card-admin">
            <header class="card-header">
                <p class="card-header-title">Randonneurs</p>
            </header>
            <div class="card-content">
                <div class="content">
                    Total de randonneurs : <?=executeSQL("SELECT count(`r_id`) FROM `nc_randonneur`",array())->fetch()[0];?>
                </div>   
                <div class="content">
                    Randonneurs non inscrit : <?=executeSQL("SELECT count(`r_id`) FROM `nc_randonneur` LEFT JOIN nc_booking ON b_r_id = r_id WHERE b_r_id IS NULL ",array())->fetch()[0];?>
                </div>
            </div>
        </div>
        <div class="card card-admin">
            <header class="card-header">
                <p class="card-header-title">Guides</p>
            </header>
            <div class="card-content">
                <div class="content">
                    Total de guides : <?=executeSQL("SELECT count(`g_numero`) FROM `nc_guide`",array())->fetch()[0];?>
                </div>   
            </div>
        </div>
    </div>
<?php }elseif (isset($_GET['action'])=='edit') {
        include 'dashboard-edit.php';
        }else {?>
    
    <div class='column'>
        <div class='card card_user'>
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
                        <p class='title is-4'><?=$_SESSION['user_firstname']." ".$_SESSION['user_lastname']?></p>
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
        }
    } else {
        header('Location: index.php');
        exit;
    }?>
    </div>       
    </main>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
    <script src='./src/edit-profile.js'></script>
</body>
</html>