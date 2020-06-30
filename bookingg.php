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
    $query = "DELETE FROM `nc_guidemeneexcursion` WHERE `ge_e_id` = ?";
    executeSQL($query,array($_POST['delete']));     
}

if(isset($_POST['book'])){
    $query = "INSERT INTO nc_guidemeneexcursion VALUE (?,?)";
    executeSQL($query,array($_POST['book'],$id));     
}

if($action =='add') {
    $query = "SELECT g_nom, g_numero FROM nc_guide WHERE g_numero = ?";
    $reponse = executeSQL($query,array($id));
    while ($donnees2 = $reponse->fetch()){
        $user = $donnees2['g_numero']." : ".$donnees2['g_nom'];
    }
    $title = "<h2 class='title is-2'>Booking excursion for guide $user</h2>";
    $content = " 
    <table class='table is-mobile is-striped'>
                        <thead>
                            <tr>
                                <th>Pos</th>
                                <th><a href='?action=list&ord=name'>Name</a></th>
                                <th>Starting point</th>
                                <th>End point</th>
                                <th><a href='?action=list&ord=dd'>Start</th>
                                <th><a href='?action=list&ord=da'>End</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            ";
                $query = "SELECT e_id,e_nom,e_point_depart,e_point_arrivee,e_date_depart,e_date_arrivee FROM nc_excursion LEFT JOIN nc_guidemeneexcursion ON ge_e_id = e_id AND ge_g_numero = ? WHERE ge_e_id IS NULL"; 
                $reponse = executeSQL($query,array($id));
                while ($donnees = $reponse->fetch()) {
                    $content .= "
                      <tr>
                            <th class='is-vcentered'>$pos</th>
                            <th class='is-vcentered'>".$donnees['e_nom']."</th>
                            <th class='is-vcentered'>".$donnees['e_point_depart']."</th>
                            <th class='is-vcentered'>".$donnees['e_point_arrivee']."</th>
                            <th class='is-vcentered'>".$donnees['e_date_depart']."</th>
                            <th class='is-vcentered'>".$donnees['e_date_arrivee']."</th>
                            <th class='is-vcentered'>
                            <form action='' method='post'>
                                <div class='field'>
                                    <div class='control'>
                                        <button type ='submit' class='button is-success' onclick=\"return confirm('Vous allez reserver l\'excursion ".$donnees['e_nom']."');\" name='book' value='".$donnees['e_id']."'>Book</button>
                                    </div>
                                </div>
                            </form>
                            </th>
                        </tr>
                    ";$pos++;
                }
                $content .= "</table>
    ";

}else {
    $title = "<h2 class='title is-2 has-text-centered'>Reservations management for guides</h2>";
    $content = "   
    <div class='column'>$title
    <div class='column'>
            <div class='column is-half  is-offset-one-quarter'>
                <div class='field'>
                    <label for='id' class='label'>Name :</label>
                </div>        
            <form action='' method='GET'>

                <div class='field is-grouped'>
                    <div class='control'>
                        <div class='select'>
                            <select name='id' id='id'>";
                    $reponse = executeSQL("SELECT `g_numero`,`g_nom` FROM `nc_guide` ORDER BY `g_nom` ASC ",array());
                    while ($donnees2 = $reponse->fetch()){
                        $content .= "<option value ='".$donnees2['g_numero']."'";
                        if ($donnees2['g_numero'] == $id) {
                            $content .= ' selected';
                        }
                        $content .= ">".$donnees2['g_numero']." : ".$donnees2['g_nom']."</option>";
                    }
                    $content .= "
                            </select>       
                        </div>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-success' value ='$id' name='show'>Show</button>
                    </div>
                </div>
            </form>
</div>
";
            if($id) {
                $content .= "
    <div class='column'>
                <div class='table-container'>
                            <table class='table is-striped'>
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th><a href='?action=list&ord=name'>Name</a></th>
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
                $query = "SELECT e_id, e_nom FROM `nc_excursion` INNER JOIN nc_guidemeneexcursion ON e_id = ge_e_id WHERE ge_g_numero = ?";
                $reponse = executeSQL($query,array($id));
                while ($donnees = $reponse->fetch()) {
                    $content .= "
                    <tr>
                        <th class='is-vcentered'>$pos</th>
                        <th class='is-vcentered'>".$donnees['e_nom']."</th>
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