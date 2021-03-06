<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"]===false || $_SESSION['admin']===true){
    header('Location: index.php');
    exit;
}else{
    $action = (isset($_GET['action']))? $_GET['action']:'list';
    require_once 'bdd.php';
    require_once 'function.php';
    include 'header.php';
    $region ='';

    if(isset($_POST['delete'])) {
        $query = "SELECT e_date_depart FROM nc_excursion INNER JOIN nc_booking ON e_id = b_e_id WHERE e_id = ? AND b_r_id = ? ";
        $date = executeSQL($query,array($_POST['delete'],$_SESSION['id']))->fetch()[0];
        $date = date_outil($date,5);
        if ($date > (date("Y-m-d"))) {
            $query = "DELETE FROM `nc_booking` WHERE b_e_id = ? AND b_r_id = ?";
            executeSQL($query,array($_POST['delete'],$_SESSION['id'])); 
        }
    }

    if(isset($_POST['book'])){
        $query = "INSERT INTO nc_booking VALUE (?,?)";
        executeSQL($query,array($_SESSION['id'],$_POST['book']));     
    }
    if($action =='add') {
        $title = "<h2 class='title is-2 has-text-centered'>Booking new excursion</h2>";
        $content = " 
        <div class='column'>$title
        <div class='table-container'>
        <table class='table is-mobile is-striped'>
                            <thead>
                                <tr>
                                    <th><a href='?action=list&ord=name'>Name</a></th>
                                    <th>Starting point</th>
                                    <th>End point</th>
                                    <th><a href='?action=list&ord=tarif'>Price</a></th>
                                    <th><a href='?action=list&ord=dd'>Start</th>
                                    <th><a href='?action=list&ord=da'>End</th>
                                    <th>Action</th>      
                                    <th>
                                        <form>
                                            <div class='control'>
                                                <button type ='submit' class='button is-danger name='cancel'>Cancel</button>
                                            </div>
                                        </form>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                ";
                    $query = "SELECT e_id,e_nom,e_tarif,e_point_depart,e_point_arrivee,e_date_depart,e_date_arrivee FROM nc_excursion LEFT JOIN nc_booking ON b_e_id = e_id AND b_r_id = ? WHERE b_e_id IS NULL AND (e_randonneurs_max - (select count(*) from nc_booking where b_e_id = e_id))>0 "; //selectionne les excursions où le randonneurs donné (?) n'est pas inscrit ET dans lesquelles il reste encore de la place
                    $reponse = executeSQL($query,array($_SESSION['id']));
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                          <tr>
                                <th class='is-vcentered'>".$donnees['e_nom']." </th>
                                <th class='is-vcentered'>".$donnees['e_point_depart']." </th>
                                <th class='is-vcentered'>".$donnees['e_point_arrivee']." </th>
                                <th class='is-vcentered'>".$donnees['e_tarif']." €</th>
                                <th class='is-vcentered'>".$donnees['e_date_depart']." </th>
                                <th class='is-vcentered'>".$donnees['e_date_arrivee']." </th>
                                <th class='is-vcentered'>
                                <div class='control'>
                                    <button class='button is-success' name='book' onclick=\"document.getElementById('id$pos').style.display='block'\" >Book</button>
                                </div>
                                <div id='id$pos' class='modal'>
                                    <form class='modal-content' action='' method='post'>
                                        <div class='container-modal'>
                                            <p class='title is-3'>Booking excursion</p>
                                            <p class='title is-4'>You are about to reserve : ".$donnees['e_nom']."</p>
                                            <div class='buttons is-centered'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-danger' name ='cancel'>Cancel</button>
                                                </div>
                                                <div class='control'>
                                                    <button type='submit' class='button is-success' name='book' value='".$donnees['e_id']."'>Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos').style.display='none'\"></button>
                                </div> 
                            </th>
                            </tr>
                        ";$pos++;
                    }
                    $content .= "</table>
        ";        
    } else {
        $title = "<h2 class='title is-2 has-text-centered'>Excursion's management</h2>";
        $content = " 
        <div class='column'>$title
        <div class='table-container'>
                    <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th><a href='?action=list&ord=name'>Name</a></th>
                            <th>Starting point</th>
                            <th>End point</th>
                            <th><a href='?action=list&ord=dd'>Start</a></th>
                            <th><a href='?action=list&ord=da'>End</a></th>
                            <th>Action</th>      
                            <th>
                            <form action='' method='get'>
                                <div class='field'>
                                    <div class='control'>
                                        <button type ='submit' class='button is-success' name='action' value='add'>Add</button>
                                    </div>
                                </div>
                            </form>
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                    ";
                    $ord =isset($_GET['ord'])?$_GET['ord']:'';
                    switch ($ord) {
                        case 'name':
                            $ord = 'e_nom';
                            break;
                        case 'dd':
                            $ord = 'e_date_depart';
                            break;
                        case 'da':
                            $ord = 'e_date_arrivee';
                            break;
                        default:
                            $ord = 'e_nom';
                            break;
                    }
                    $query = "SELECT DISTINCT e_id, e_nom, e_point_depart, e_point_arrivee, e_date_depart, e_date_arrivee FROM `nc_excursion` INNER JOIN nc_booking ON e_id = b_e_id WHERE b_r_id = ? ORDER BY $ord";
                    $reponse = executeSQL($query,array($_SESSION['id']));
                    
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                        <tr>
                            <th class='is-vcentered'>$pos</th>
                            <th class='is-vcentered'>".$donnees['e_nom']." </th>
                            <th class='is-vcentered'>".$donnees['e_point_depart']." </th>
                            <th class='is-vcentered'>".$donnees['e_point_arrivee']." </th>
                            <th class='is-vcentered'>".$donnees['e_date_depart']." </th>
                            <th class='is-vcentered'>".$donnees['e_date_arrivee']." </th>
                            <th class='is-vcentered'>
                                <div class='control'>
                                    <button class='button is-danger' name='delete' onclick=\"document.getElementById('id$pos').style.display='block'\" >Delete</button>
                                </div>
                                <div id='id$pos' class='modal'>
                                    <form class='modal-content' action='' method='post'>
                                        <div class='container-modal'>
                                            <p class='title is-3'>Booking excursion</p>
                                            <p class='title is-4'>You are about to delete : ".$donnees['e_nom']."</p>
                                            <div class='buttons is-centered'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-success' name ='cancel'>Cancel</button>
                                                </div>
                                                <input type='hidden' name='e_date' value='".$donnees['e_date_depart']."'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-danger' name='delete' value='".$donnees['e_id']."'>Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos').style.display='none'\"></button>
                                </div> 
                            </th>
                        </tr>
                        ";$pos++;
                    }
                    $content .= "</table>";
                }
            }
            echo "

            $content
        </div>   
            </div>       
            </div>       
            </div>       
            </main>
        <script src='./src/randonneur.js'></script>
        <script src='./src/script-menu.js'></script>
    </body>
</html>";