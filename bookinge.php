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
            $erreurs = "<p class='verif'>Sorry, there are no more places available for this excursion</p>";
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
    $title = "<h2 class='title is-2 has-text-centered'>Booking hikers for the excursion :  $e_nom</h2>";
    $content = "   
    <div class='column'><div>$erreurs</div>$title
    <div class='table-container'>
        <table class='table is-mobile is-striped'>
            <thead>
            <tr>
                <th><a href='?action=list&ord=name'>First Name</a></th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Action</th>
                <th>
                    <form>
                        <div class='control'>
                            <button type ='submit' class='button is-danger'>Cancel</button>
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
                                <div class='control'>
                                    <button class='button is-success' name='book' onclick=\"document.getElementById('id$pos').style.display='block'\" >Book</button>
                                </div>
                                <div id='id$pos' class='modal'>
                                    <form class='modal-content' action='' method='post'>
                                        <div class='container-modal'>
                                            <p class='title is-3'>Booking excursion</p>
                                            <p class='title is-4'>You are about to reserve : $e_nom</p>
                                            <div class='buttons is-centered'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-danger' name ='cancel'>Cancel</button>
                                                </div>
                                                <div class='control'>
                                                    <button type='submit' class='button is-success' name='book' value='".$donnees['r_id']."'>Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos').style.display='none'\"></button>
                                </div> 
                            </th>
                            </tr>
                        ";
                    }
                    $content .= "</table>
                </div>
                ";
    }elseif ($_GET['type']=='g'){
    $title = "<h2 class='title is-2'>Booking a guide for the excursion :  $e_nom</h2>";
    $content = "   
    <div class='column'>$title
    <div class='table-container'>
        <table class='table is-mobile is-striped'>
            <thead>
            <tr>
                <th><a href='?action=list&ord=name'>Number</a></th>
                <th>Name</th>
                <th>Phone</th>
                <th>Action</th>          
                <th>
                    <form>
                        <div class='control'>
                            <button type ='submit' class='button is-danger'>Cancel</button>
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
                                <div class='control'>
                                    <button class='button is-success' name='book' onclick=\"document.getElementById('id$pos').style.display='block'\" >Book</button>
                                </div>
                                <div id='id$pos' class='modal'>
                                    <form class='modal-content' action='' method='post'>
                                        <div class='container-modal'>
                                            <p class='title is-3'>Booking excursion</p>
                                            <p class='title is-4'>You are about to reserve</p>
                                            <div class='buttons is-centered'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-danger'>Cancel</button>
                                                </div>
                                                <div class='control'>
                                                    <button type='submit' class='button is-success' name='book' value='".$donnees['g_numero']."'>Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos').style.display='none'\"></button>
                                </div> 
                            </th>
                            </tr>
                        ";
                    }
                    $content .= "</table>
                </div>
                ";

    }

}else {
    $title = "<h2 class='title is-2 has-text-centered'>Reservations management for excursions</h2>";
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
                                    <th>First Name</th>
                                    <th>Last Name</th>
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
                while ($donnees = $reponse->fetch()) {
                    
                    $content .= "
                    <tr id='bookinger'>
                        <th class='is-vcentered'>".$donnees['r_nom']." </th>
                        <th class='is-vcentered'>".$donnees['r_prenom']." </th>
                        <th class='is-vcentered'>".$donnees['r_email']." </th>
                        <th>
                            <div class='control'>
                                <button class='button is-danger' name='delete' onclick=\"document.getElementById('id$pos$pos').style.display='block'\" >Delete</button>
                            </div>
                            <div id='id$pos$pos' class='modal'>
                                <form class='modal-content' action='' method='post'>
                                    <div class='container-modal'>
                                        <p class='title is-3'>Delete Reservation</p>
                                        <p class='title is-4'>Are you sure you want to delete this hiker's reservation ?</p>
                                        <div class='buttons is-centered'>
                                            <div class='control'>
                                                <button type='submit' class='button is-success'>Cancel</button>
                                            </div>
                                                <input type ='hidden' name='type' value='r'>
                                            <div class='control'>
                                                <button type='submit' class='button is-danger' name='delete' value='".$donnees['r_id']."'>Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos$pos').style.display='none'\"></button>
                            </div> 
                        </th>
                    </tr>
                    ";
                    $pos++;
                }
                $content .= "</table>
                </div>
                </div>
                <div class='table-container'>

                            <table class='table is-mobile is-striped'>
                                <caption><h3 class='title is-3'>Guides list</h3></caption>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Number</th>
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
                while ($donnees = $reponse->fetch()) {
                    
                    $content .= "
                    <tr id='bookingeg'>
                        <th class='is-vcentered'>".$donnees['g_nom']." </th>
                        <th class='is-vcentered'>".$donnees['g_numero']." </th>
                        <th>
                            <div class='control'>
                                <button class='button is-danger' name='delete' onclick=\"document.getElementById('id$pos$pos$pos').style.display='block'\" >Delete</button>
                            </div>
                            <div id='id$pos$pos$pos' class='modal'>
                                <form class='modal-content' action='' method='post'>
                                    <div class='container-modal'>
                                        <p class='title is-3'>Delete Reservation</p>
                                        <p class='title is-4'>Are you sure you want to delete this guide's reservation ?</p>
                                        <div class='buttons is-centered'>
                                            <div class='control'>
                                                <button type='submit' class='button is-success'>Cancel</button>
                                            </div>
                                                <input type ='hidden' name='type' value='g'>
                                            <div class='control'>
                                                <button type='submit' class='button is-danger' name='delete' value='".$donnees['g_numero']."'>Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos$pos$pos').style.display='none'\"></button>
                            </div> 
                        </th>
                    </tr>
                    ";
                    $pos++;
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
            <script src='./src/script-menu.js'></script>
            </body>
</html>";