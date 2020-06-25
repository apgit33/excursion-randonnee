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
        $query = "DELETE FROM `nc_randonneur` WHERE r_id = ?";
        $query2 = "DELETE FROM `nc_booking` WHERE b_r_id = ?";

        doIt2($query,$_POST['delete'],'');
        doIt2($query2,$_POST['delete'],'');
    }

    if(isset($_POST['modify'])) {

        $query = "UPDATE `nc_randonneur` SET `r_nom` = ?,`r_prenom` = ?,`r_email` = ?,`r_password` = ? WHERE `r_id` = ?";

        doIt6($query,$_POST['nom_randonneur'],$_POST['prenom_randonneur'],$_POST['email_randonneur'],$_POST['password_randonneur'],$_POST['modify'],'');
    }

    if(isset($_POST['add_entry'])) {

        $query = "INSERT INTO `nc_randonneur` (`r_nom`,`r_prenom`,`r_email`,`r_password`) VALUES (?,?,?,?)";
        doIt4($query,$_POST['nom_randonneur'],$_POST['prenom_randonneur'],$_POST['email_randonneur'],$_POST['password_randonneur']);
    }



    if($action =='add') {
        $title = "<h2 class='title is-2'>Ajout d'un randonneur</h2>";
        $content = "
        <form action='' method='post' id='edit_form'>
            <div class='field'>
                <label for='nom_randonneur'>Nom :</label>
                <div class='control'>
                    <input type='text' name='nom_randonneur' id='nom_randonneur' class='input'>         
                </div>
            </div>    
            <div class='field'>
                <label for='prenom_randonneur'>Prenom :</label>
                <div class='control'>
                    <input type='text' name='prenom_randonneur' id='prenom_randonneur' class='input'>
                </div>
            </div>    
            <div class='field'>
                <label for='email_randonneur'>Email :</label>
                <div class='control'>
                    <input type='email' name='email_randonneur' id='email_randonneur' class='input'>
                </div>
            </div> 
            <div class='field'>
            <label for='password_randonneur'>password :</label>
                <div class='control'>
                    <input type='password' name='password_randonneur' id='password_randonneur' class='input'>
                </div>
            </div> 
            <div class='field is-grouped'>
                <div class='control'>
                    <button type ='submit' class='button is-success' name='add_entry'>Ajouter</button>
                </div>
                <div class='control'>
                    <button type ='reset' class='button is-danger'>Reset</button>
                </div>
            </div>
        </form>";
    } elseif(isset($_POST['edit'])) {
            $query = "SELECT * FROM `nc_randonneur` WHERE r_id = ?";
            $reponse = doIt2($query,$_POST['edit'],'');
            
            if ($donnees = $reponse->fetch()) {
                $title = "<h2 class='title is-2'>Edition de ".$donnees['r_nom']." ".$donnees['r_prenom']."</h2>";
                $content = "
                    <form action='' method='post' id='edit_form'>
                        <div class='field'>
                            <label for='nom_randonneur'>Nom :</label>
                            <div class='control'>
                                <input class='input' type='text' name='nom_randonneur' id='nom_randonneur' value='".$donnees['r_nom']."'>         
                            </div>
                        </div>
                        <div class='field'>
                            <label for='prenom_randonneur'>Prenom :</label>
                            <div class='control'>
                                <input class='input' type='text' name='prenom_randonneur' id='prenom_randonneur' value='".$donnees['r_prenom']."'>
                            </div>
                        </div>
                        <div class='field'>
                            <label for='email_randonneur'>Email :</label>
                            <div class='control'>
                                <input class='input' type='email' name='email_randonneur' id='email_randonneur' value='".$donnees['r_email']."'>
                            </div>
                        </div>
                        <div class='field'>
                            <label for='password_randonneur'>Password :</label>
                            <div class='control'>
                                <input class='input' type='password' name='password_randonneur' id='password_randonneur' value='".$donnees['r_password']."'>
                            </div>
                        </div>
                        <div class='field is-grouped'>
                            <div class='control'>
                                <button type ='submit' class='button is-success' name='modify' value='".$donnees['r_id']."'>Modifier</button>
                            </div>
                            <div class='control'>
                                <button type ='submit' class='button is-danger'>Annuler</button>
                            </div>
                        </div>
                    </form>";
            }
        } else {
            $title = "<h2 class='title is-2'>Affichage des randonneurs</h2>";
            $content = " 
                <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    ";

                    $query = "SELECT * FROM `nc_randonneur` ORDER BY `r_nom`";

                    $reponse = doIt2($query,'','');
                    
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                        <tr>
                            <th class='is-vcentered'>".$donnees['r_nom']." </th>
                            <th class='is-vcentered'>".$donnees['r_prenom']." </th>
                            <th class='is-vcentered'>".$donnees['r_email']." </th>
                            <th class='is-vcentered'>".$donnees['r_password']." </th>
                            <th>
                                <form action='' method='post'>
                                    <div class='field is-grouped'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-rounded is-success' name='edit' value='".$donnees['r_id']."'>Edit</button>
                                        </div>
                                        <div class='control'>
                                            <button type ='submit' class='button is-rounded is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['r_id']."'>Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </th>
                        </tr>
                        ";
                    }
                    $content .= "</table>";

                    ?>
                  <?php
            }
}
echo "
    <div class='column'>
        $title
        $content
    </div>";
 
include 'footer.php';