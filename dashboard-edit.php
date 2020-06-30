<?php 

$query = "SELECT r_nom, r_prenom, r_email, r_password FROM `nc_randonneur` WHERE r_id = ?";
$reponse = executeSQL($query,array($_SESSION['id']));

if ($donnees = $reponse->fetch()) { ?>
    <div class='column'>
    <h2 class='title is-2 has-text-centered'>Edition of <?=$donnees['r_nom']." ".$donnees['r_prenom']?></h2>
        <div class='column is-offset-one-quarter'>
                <div class='column is-half '>
                    <form action='' method='post' id='edit_form'>
            <div class='field'>
                <label for='nom_randonneur' class='label'>First Name :</label>
                <div class='control'>
                    <input class='input' type='text' name='nom_randonneur' id='nom_randonneur' value='<?=$donnees['r_nom']?>' required>         
                </div>
                <div id='checkfirstname' class='verif'></div>
            </div>
            <div class='field'>
                <label for='prenom_randonneur' class='label'>Last Name :</label>
                <div class='control'>
                    <input class='input' type='text' name='prenom_randonneur' id='prenom_randonneur' value='<?=$donnees['r_prenom']?>' required>
                </div>
                <div id='checklastname' class='verif'></div>
            </div>
            <div class='field'>
                <label for='email_randonneur' class='label'>Email :</label>
                <div class='control'>
                    <input class='input' type='email' name='email_randonneur' id='email_randonneur' value='<?=$donnees['r_email']?>' required>
                </div>
                <div id='checkemail' class='verif'></div>
            </div>
            <div class='field'>
                <label for='password_randonneur' class='label'>Password :</label>
                <div class='control'>
                    <input class='input' type='password' name='password_randonneur' id='password_randonneur' value='<?=$donnees['r_password']?>' required>
                </div>
                <div id='checkpass' class='verif'></div>
            </div>
            <div class='field is-grouped'>
                <div class='control'>
                    <button type ='submit' class='button is-success' name='edit'>Edit</button>
                </div>
                <div class='control'>
                    <a href='index.php' class='button is-danger'>Cancel</a>
                </div>
            </div>
        </form>
    </div>
    </div>
    </div>
    <?php }?>