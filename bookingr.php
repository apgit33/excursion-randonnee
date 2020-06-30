<?php
session_start();
if (!isset($_SESSION["admin"]) || $_SESSION["admin"]===false){
    header('Location: index.php');
    exit;
}else{
    $id = (isset($_GET['id']))? $_GET['id']:'';
    $action = (isset($_GET['action']))? $_GET['action']:'list';

    require_once 'bdd.php';
    require_once 'function.php';
    include 'header.php';

if(isset($_POST['delete'])) {
    $query = "DELETE FROM nc_booking WHERE b_e_id = ? AND b_r_id = ?";
    executeSQL($query,array($_POST['delete'],$id));     
}

if(isset($_POST['book'])){
    $query = "INSERT INTO nc_booking VALUE (?,?)";
    executeSQL($query,array($id,$_POST['book']));     
}

if($action =='add') {
    $query = "SELECT r_nom, r_prenom FROM nc_randonneur WHERE r_id = ?";
    $reponse = executeSQL($query,array($id));
    while ($donnees2 = $reponse->fetch()){
        $user = $donnees2['r_nom']." ".$donnees2['r_prenom'];
    }
    $title = "<h2 class='title is-2 has-text-centered'>Ajout d'une réservations pour $user</h2>";
    $content = "   
    <div class='column'>$title
    <div class='table-container'>
    <table class='table is-mobile is-striped'>
                        <thead>
                            <tr>
                                <th>Pos</th>      
                                <th>Nom</th>
                                <th>Point de départ</th>
                                <th>Point d'arrivée</th>
                                <th>Tarif</th>
                                <th>Date de départ</th>
                                <th>Date d'arrivée</th>
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
                $reponse = executeSQL($query,array($id));
                while ($donnees = $reponse->fetch()) {
                    $content .= "
                      <tr>
                            <th class='is-vcentered'>$pos</th>
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
                    ";$pos++;
                }
                $content .= "</table>
                </div>

    ";

}else {
    $title = "<h2 class='title is-2 has-text-centered'>Gestion des réservations pour les randonneurs</h2>";
    $content = "   
    <div class='column'>$title
    <div class='column is-offset-one-quarter'>
            <div class='column is-half '>
                <div class='field'>
                    <label for='id' class='label'>Nom :</label>
                </div>        
            <form action='' method='GET'>
                <div class='field is-grouped'>
                    <div class='control'>
                        <div class='select'>
                            <select name='id' id='id'>";
                    $reponse = executeSQL("SELECT `r_id`,`r_nom`,`r_prenom` FROM `nc_randonneur` ORDER BY `nc_randonneur`.`r_nom` ASC ",array());
                    while ($donnees2 = $reponse->fetch()){
                        $content .= "<option value ='".$donnees2['r_id']."'";
                        if ($donnees2['r_id'] == $id) {
                            $content .= ' selected';
                        }
                        $content .= ">".$donnees2['r_nom']." ".$donnees2['r_prenom']."</option>";
                    }
                    $content .= "
                            </select>       
                        </div>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-success' value =$id>Show</button>
                    </div>
                </div>
            </form>
</div>
            ";
            if($id) {
                $content .= "
    <div class='column is-half'>

                            <table class='table is-mobile is-striped'>
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th><a href='?action=list&ord=name'>Nom</a></th>
                                    <th>Action</th>
                                    <th>
                                        <div class='field'>
                                            <form action='' method='get'>
                                                <div class='control'>
                                                    <input type ='hidden' name='id' value=$id>
                                                </div>
                                                <div class='control'>
                                                    <button type ='submit' class='button is-success' name='action' value='add'>Add</button>
                                                </div>
                                            </form>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            ";
                $query = "SELECT DISTINCT e_id, e_nom FROM `nc_excursion` INNER JOIN nc_booking ON e_id = b_e_id WHERE b_r_id = ?";
                $reponse = executeSQL($query,array($id));
                while ($donnees = $reponse->fetch()) {
                    $content .= "
                    <tr>
                        <th class='is-vcentered'>$pos</th>
                        <th class='is-vcentered'>".$donnees['e_nom']." </th>
                        <th>
                            <form action='' method='post'>
                                <div class='field'>
                                    <div class='control'>
                                        <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['e_id']."'>Delete</button>
                                    </div>
                                </div>
                            </form>
                        </th>
                    </tr>
                    ";$pos++;
                }
                $content .= "</table>
                </div>
                </div>
                ";
            }


        }
        }
echo "
        $content
    </div>   
    </div>       
</div>       
</main>
</body>
</html>";