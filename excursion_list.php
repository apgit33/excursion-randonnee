<?php
session_start();
if (!isset($_SESSION["login"]) || $_SESSION["login"]===false){
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
        $title = "<h2 class='title is-2 has-text-centered'>Ajout d'une réservation</h2>";
        $content = " 
        <div class='column'>$title
        <div class='table-container'>
        <table class='table is-mobile is-striped'>
                            <thead>
                                <tr>
                                    <th><a href='?action=list&ord=name'>Nom</a></th>
                                    <th>Point de départ</th>
                                    <th>Point d'arrivée</th>
                                    <th><a href='?action=list&ord=tarif'>Tarif</a></th>
                                    <th><a href='?action=list&ord=dd'>Date de départ</th>
                                    <th><a href='?action=list&ord=da'>Date d'arrivée</th>
                                    <th>Action</th>      
                                    <th>
                                        <form>
                                            <div class='control'>
                                                <button type ='submit' class='button is-danger'>Annuler</button>
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
                                <th class='is-vcentered'>".$donnees['e_tarif']." </th>
                                <th class='is-vcentered'>".$donnees['e_date_depart']." </th>
                                <th class='is-vcentered'>".$donnees['e_date_arrivee']." </th>
                                <th class='is-vcentered'>
                                <form action='' method='post'>
                                    <div class='field'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-success' onclick=\"return confirm('Vous allez reserver l\'excursion ".$donnees['e_nom']."');\" name='book' value='".$donnees['e_id']."'>Reserver</button>
                                        </div>
                                    </div>
                                </form>
                                </th>
                            </tr>
                        ";
                    }
                    $content .= "</table>
        ";        
    } else {
        $title = "<h2 class='title is-2 has-text-centered'>Gestion de vos excursions</h2>";
        $content = " 
        <div class='column'>$title
        <div class='table-container'>
                    <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th><a href='?action=list&ord=name'>Nom</a></th>
                            <th>Point de départ</th>
                            <th>Point d'arrivée</th>
                            <th><a href='?action=list&ord=dd'>Date de départ</a></th>
                            <th><a href='?action=list&ord=da'>Date d'arrivée</a></th>
                            <th>Action</th>      
                            <th>
                            <form action='' method='get'>
                                <div class='field'>
                                    <div class='control'>
                                        <button type ='submit' class='button is-success' name='action' value='add'>Nouvelle réservation</button>
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
                            <th>
                            <form action='' method='post'>
                                <div class='field'>
                                    <div class='control'>
                                        <input type='hidden' name='e_date' value='".$donnees['e_date_depart']."'>
                                    </div>
                                    <div class='control'>
                                        <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['e_id']."'>Delete</button>
                                    </div>
                                </div>
                            </form>
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
    </body>
</html>";