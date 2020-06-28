<?php 

$query = "SELECT r_nom, r_prenom, r_email, r_password FROM `nc_randonneur` WHERE r_id = ?";
$reponse = executeSQL($query,array($_SESSION['id']));

if ($donnees = $reponse->fetch()) { ?>
    <div class='column'>
    <h2 class='title is-2'>Edition de <?=$donnees['r_nom']." ".$donnees['r_prenom']?></h2>
        <form action='' method='post' id='edit_profile'>
            <div class='field'>
                <label for='nom_randonneur' class='label'>Nom :</label>
                <div class='control'>
                    <input class='input' type='text' name='nom_randonneur' id='nom_randonneur' value='<?=$donnees['r_nom']?>' onblur='verifPseudo(this)'>         
                </div>
            </div>
            <div class='field'>
                <label for='prenom_randonneur' class='label'>Prenom :</label>
                <div class='control'>
                    <input class='input' type='text' name='prenom_randonneur' id='prenom_randonneur' value='<?=$donnees['r_prenom']?>'>
                </div>
            </div>
            <div class='field'>
                <label for='email_randonneur' class='label'>Email :</label>
                <div class='control'>
                    <input class='input' type='email' name='email_randonneur' id='email_randonneur' value='<?=$donnees['r_email']?>'>
                </div>
            </div>
            <div class='field'>
                <label for='password_randonneur' class='label'>Password :</label>
                <div class='control'>
                    <input class='input' type='password' name='password_randonneur' id='password_randonneur' value='<?=$donnees['r_password']?>'>
                </div>
            </div>
            <div class='field is-grouped'>
                <div class='control'>
                    <button type ='submit' class='button is-success' name='modify'>Modifier</button>
                </div>
                <div class='control'>
                    <a href='index.php' class='button is-danger'>Annuler</a>
                </div>
            </div>
        </form>
        <ul id='verif'></ul>
    </div>
    <?php }?>