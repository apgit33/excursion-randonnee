<?php
session_start();
if (!isset($_SESSION["admin"]) || $_SESSION["admin"]===false){
    header('Location: index.php');
    exit;
}else{
    $id = (isset($_GET['id']))? $_GET['id']:'';
    $action = (isset($_GET['action']))? $_GET['action']:'list';
    $erreurs ='';

    require_once 'bdd.php';
    require_once 'function.php';
    include 'header.php';

if(isset($_POST['delete'])) {
    if ($_POST['type']=='r') {
        $query = "DELETE FROM `nc_booking` WHERE `b_e_id` = ? AND b_r_id = ?";
        executeSQL($query,array($id,$_POST['delete'])); 
    } else if ($_POST['type']=='g') {
        $query = "DELETE FROM `nc_guidemeneexcursion` WHERE `ge_e_id` = ? AND ge_g_numero = ?";
        executeSQL($query,array($id,$_POST['delete'])); 
    }
}

if(isset($_POST['book'])){
    if ($_GET['type']=='r') {
        $max=0;
        $query = "SELECT (SELECT `e_randonneurs_max` FROM `nc_excursion` WHERE `e_id`= ?) - count(r_id) as nombre_restant FROM `nc_randonneur` INNER JOIN nc_booking ON r_id = b_r_id WHERE b_e_id = ? ";
        $reponse = executeSQL($query,array($id,$id));
        while($donnees = $reponse->fetch()) {
            $max = $donnees['nombre_restant'];
        }
        if($max>0) {
            $query = "INSERT INTO nc_booking VALUE (?,?)";
            executeSQL($query,array($_POST['book'],$id));     
        }else{
            $erreurs = "<p>Sorry, there are no more places available for this excursion</p>";
        }
    } else if ($_GET['type']=='g') {
        $query = "INSERT INTO nc_guidemeneexcursion VALUE (?,?)";
        executeSQL($query,array($id,$_POST['book']));     
    }
}

if($action =='add' && isset($_GET['id']) && isset($_GET['type'])) {
    $query = "SELECT e_nom FROM nc_excursion WHERE e_id = ?";
    $reponse = executeSQL($query,array($id));
    while ($donnees2 = $reponse->fetch()){
        $e_nom = $donnees2['e_nom'];
    }
    if($_GET['type']=='r') {
    $title = "<h2 class='title is-2 has-text-centered'>Ajout d'un randonneur pour l'excursion :  $e_nom</h2>";
    $content = "   
    <div class='column'>$title
    <div class='table-container'>
        <table class='table is-mobile is-striped'>
            <thead>
            <tr>
                <th><a href='?action=list&ord=name'>Nom</a></th>
                <th>Prénom</th>
                <th>Email</th>
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
                    $query = "SELECT r_id,r_nom,r_prenom, r_email FROM nc_randonneur LEFT JOIN nc_booking ON r_id = b_r_id AND b_e_id = ? WHERE b_r_id IS NULL"; 
                    $reponse = executeSQL($query,array($id));
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                        <tr>
                                <th class='is-vcentered'>".$donnees['r_nom']." </th>
                                <th class='is-vcentered'>".$donnees['r_prenom']." </th>
                                <th class='is-vcentered'>".$donnees['r_email']." </th>
                                <th class='is-vcentered'>
                                <form action='' method='post'>
                                    <div class='field'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-success' onclick=\"return confirm('Confirmez la réservation');\" name='book' value='".$donnees['r_id']."'>Reserver</button>
                                        </div>
                                    </div>
                                </form>
                                </th>
                            </tr>
                        ";
                    }
                    $content .= "</table>
                </div>
                ";
    }elseif ($_GET['type']=='g'){
    $title = "<h2 class='title is-2'>Ajout d'un guide pour l'excursion :  $e_nom</h2>";
    $content = "   
    <div class='column'>$title
    <div class='table-container'>
        <table class='table is-mobile is-striped'>
            <thead>
            <tr>
                <th><a href='?action=list&ord=name'>Numéro</a></th>
                <th>Nom</th>
                <th>Téléphone</th>
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
                    $query = "SELECT g_numero,g_nom,g_telephone FROM nc_guide LEFT JOIN nc_guidemeneexcursion ON g_numero = ge_g_numero AND ge_e_id = ? WHERE ge_e_id IS NULL"; 
                    $reponse = executeSQL($query,array($id));
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                        <tr>
                                <th class='is-vcentered'>".$donnees['g_numero']." </th>
                                <th class='is-vcentered'>".$donnees['g_nom']." </th>
                                <th class='is-vcentered'>".$donnees['g_telephone']." </th>
                                <th class='is-vcentered'>
                                <form action='' method='post'>
                                    <div class='field'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-success' onclick=\"return confirm('Confirmez la réservation');\" name='book' value='".$donnees['g_numero']."'>Reserver</button>
                                        </div>
                                    </div>
                                </form>
                                </th>
                            </tr>
                        ";
                    }
                    $content .= "</table>
                </div>
                ";

    }

}else {
    $title = "<h2 class='title is-2 has-text-centered'>Gestion des réservations pour les excursions</h2>";
    $content = "   
    <div class='column'>$title
    <div class='column'>
            <div class='column is-half  is-offset-one-quarter'>
                <div class='field'>
                    <label for='id' class='label'>Nom :</label>
                </div>        
            <form action='' method='GET'>


                <div class='field is-grouped'>
                    <div class='control'>
                        <div class='select'>
                            <select name='id' id='id'>";
                    $reponse = executeSQL("SELECT `e_nom`,`e_id` FROM `nc_excursion` ORDER BY `e_nom` ASC ",array());
                    while ($donnees2 = $reponse->fetch()){
                        $content .= "<option value ='".$donnees2['e_id']."'";
                        if ($donnees2['e_id'] == $id) {
                            $content .= ' selected';
                        }
                        $content .= ">".$donnees2['e_nom']."</option>";
                    }
                    $content .= "
                            </select>       
                        </div>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-success' value ='$id'>Show</button>
                    </div>
                </div>
                </form>
                </div>
                ";
            if($id) {
                $max=0;
                $query = "SELECT (SELECT `e_randonneurs_max` FROM `nc_excursion` WHERE `e_id`= ?) - count(r_id) as nombre_restant FROM `nc_randonneur` INNER JOIN nc_booking ON r_id = b_r_id WHERE b_e_id = ? ";
                $reponse = executeSQL($query,array($id,$id));
                while($donnees = $reponse->fetch()) {
                    $max = $donnees['nombre_restant'];
                }
                $content .= "
    <div class='column'>
                <div class='table-container'>
                            <table class='table is-striped'>
                            <caption><h3 class='title is-3'>Hikers list</h3></caption>
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th>Nom</th>
                                    <th>Prenom</th>
                                    <th>Email</th>
                                    <th>Action</th>";
                                    if($max>0) {
                                        $content.= "<th>
                                        <div class='field'>
                                            <form action='' method='get'>
                                                <div class='field'>
                                                    <div class='control'>
                                                        <input type ='hidden' name='id' value=$id>
                                                    </div>                                   
                                                    <div class='control'>
                                                        <input type ='hidden' name='type' value='r'>
                                                    </div>
                                                    <div class='control'>
                                                        <button type ='submit' class='button is-success' name='action' value='add'>Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </th>";
                                    }
                                    $content.= "
                                </tr>
                            </thead>
                            <tbody>
                            ";


                $query = "SELECT r_id,r_nom,r_prenom,r_email FROM `nc_randonneur` INNER JOIN nc_booking ON r_id = b_r_id WHERE b_e_id = ?";
                $reponse = executeSQL($query,array($id));
                $i = 1;
                while ($donnees = $reponse->fetch()) {
                    
                    $content .= "
                    <tr>
                        <th class='is-vcentered'>$i</th>
                        <th class='is-vcentered'>".$donnees['r_nom']." </th>
                        <th class='is-vcentered'>".$donnees['r_prenom']." </th>
                        <th class='is-vcentered'>".$donnees['r_email']." </th>
                        <th>
                            <form action='' method='post'>
                                <div class='field'>
                                <div class='control'>
                                    <input type ='hidden' name='type' value='r'>
                                </div>
                                    <div class='control'>
                                        <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['r_id']."'>Delete</button>
                                    </div>
                                </div>
                            </form>
                        </th>
                    </tr>
                    ";
                    $i++;
                }
                $content .= "</table>
                </div>
                </div>
                ";


                $content .= "
                <div class='table-container'>

                            <table class='table is-mobile is-striped'>
                                <caption><h3 class='title is-3'>Guides list</h3></caption>
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th>Nom</th>
                                    <th>Telephone</th>
                                    <th>Action</th>
                                    <th>
                                        <div class='field'>
                                            <form action='' method='get'>
                                                <div class='field'>
                                                    <div class='control'>
                                                        <input type ='hidden' name='id' value=$id>
                                                    </div>                                   
                                                    <div class='control'>
                                                        <input type ='hidden' name='type' value='g'>
                                                    </div>
                                                    <div class='control'>
                                                        <button type ='submit' class='button is-success' name='action' value='add'>Add</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            ";
                $query = "SELECT g_nom,g_numero FROM `nc_guide` INNER JOIN nc_guidemeneexcursion ON g_numero = ge_g_numero WHERE ge_e_id = ?";
                $reponse = executeSQL($query,array($id));
                $i = 1;
                while ($donnees = $reponse->fetch()) {
                    
                    $content .= "
                    <tr>
                        <th class='is-vcentered'>$i</th>
                        <th class='is-vcentered'>".$donnees['g_nom']." </th>
                        <th class='is-vcentered'>".$donnees['g_numero']." </th>
                        <th>
                            <form action='' method='post'>
                                <div class='field'>
                                <div class='control'>
                                    <input type ='hidden' name='type' value='g'>
                                </div>
                                    <div class='control'>
                                        <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['g_numero']."'>Delete</button>
                                    </div>
                                </div>
                            </form>
                        </th>
                    </tr>
                    ";
                    $i++;
                }
                $content .= "</table>
                </div>
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