<?php
session_start();

if (!isset($_SESSION["admin"]) || $_SESSION["admin"]===false){
    header('Location: index.php');
    exit;
}else{
    $action = (isset($_GET['action']))? $_GET['action']:'list';
    require_once 'bdd.php';
    require_once 'function.php';
    include 'header.php';
    if(isset($_POST['delete'])) {
        $query = "DELETE FROM `nc_guide` WHERE g_numero = ?";
        $reponse = doIt2($query,$_POST['delete'],'');
    }


    if(isset($_POST['modify'])) {

        $query = "UPDATE `nc_guide` SET `g_nom` = ?,`g_telephone` = ? WHERE `g_numero` = ?";
        doIt4($query,$_POST['nom_guide'],$_POST['telephone'],$_POST['modify'],'');
    }

    if(isset($_POST['add_entry'])) {
        $query = "INSERT INTO `nc_guide` (`g_nom`,`g_telephone`) VALUES (?,?)";
        doIt2($query,$_POST['nom_guide'],$_POST['telephone']);
    }

    if($action =='add') {
        $title = "<h2 class='title is-2'>Ajout d'un guide</h2>";
        $content = "
            <form action='' method='post' id='edit_form'>
                <div class='field'>
                    <label for='nom_guide' class='label'>Nom :</label>
                    <div class='control'>
                        <input class='input' type='text' name='nom_guide' id='nom_guide'>         
                    </div>
                </div>
                <div class='field'>
                    <label for='telephone' class='label'>Telephone :</label>
                    <div class='control'>
                        <input class='input' type='text' name='telephone' id='telephone'>
                    </div>
                </div>
                <div class='field is-grouped'>
                    <div class='control'>
                        <button type ='submit' class='button is-success' name='add_entry'>Ajouter</button>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-danger'>Annuler</button>
                    </div>
                </div>
            </form>";
    } elseif(isset($_POST['edit'])) {

        $query = "SELECT g_numero, g_nom, g_telephone FROM `nc_guide` WHERE g_numero = ?";
        $reponse = doIt2($query,$_POST['edit'],'');
        
        if ($donnees = $reponse->fetch()) {
            $title = "<h2 class='title is-2'>Edition du guide ".$donnees['g_nom'].", n ° : ".$donnees['g_numero']."</h2>";
            $content = "
                <form action='' method='post' id='edit_form'>
                    <div class='field'>
                        <label for='nom_guide' class='label'>Nom</label>
                        <div class='control'>
                            <input class='input' type='text' name='nom_guide' id='nom_guide' value='".$donnees['g_nom']."'>         
                        </div>
                    </div>
                    <div class='field'>
                        <label for='telephone' class='label'>telephone</label>
                        <div class='control'>
                            <input class='input' type='text' name='telephone' id='telephone' value='".$donnees['g_telephone']."'>
                        </div>
                    </div>
                    <div class='field is-grouped'>
                        <div class='control'>
                            <button type ='submit' class='button is-success' name='modify' value='".$donnees['g_numero']."'>Modifier</button>
                        </div>
                        <div class='control'>
                            <button type ='submit' class='button is-danger'>Annuler</button>
                        </div>
                    </div>
                </form>";
        }
    } else {
        $title = "<h2 class='title is-2'>Affichage des guides</h2>";
        $content = "
                <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Telephone</th>
                        </tr>
                    </thead>
                    <tbody>
                    ";

                    $query = "SELECT g_numero, g_nom, g_telephone FROM `nc_guide` ORDER BY `g_nom`";

                    $reponse = doIt2($query,'','');
                    
                    while ($donnees = $reponse->fetch()) {
                        $id=$donnees['g_numero'];
                        $content .= "
                        <tr>
                            <th class='is-vcentered'>".$id." </th>
                            <th class='is-vcentered'>".$donnees['g_nom']." </th>
                            <th class='is-vcentered'>".$donnees['g_telephone']." </th>
                            <th>
                                <form action='' method='post'>
                                    <div class='field is-grouped'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-success' name='edit' value='$id'>Edit</button>
                                        </div>
                                        <div class='control'>
                                            <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='$id'>Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </th>
                        </tr>
                        ";
                    }
        $content .= "</table>";
    }   
}

echo "
    <div class='column'>
        $title
        $content
    </div>";

include 'footer.php';