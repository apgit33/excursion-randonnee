<?php
include 'header.php';

if(isset($_POST['delete'])) {
    $query = "DELETE FROM `randonneur` WHERE id_randonneur = ?";
    // $reponse = doIt2($query,$_POST['delete'],'');
}

if(isset($_POST['modify'])) {
    $_admin = (isset($_POST['_admin']))? '1': '0';

    $query = "UPDATE `randonneur` SET `nom_randonneur` = ?,`prenom_randonneur` = ?,`email_randonneur` = ?,`password_randonneur` = ?, `_admin`= ? WHERE `id_randonneur` = ?";

    doIt6($query,$_POST['nom_randonneur'],$_POST['prenom_randonneur'],$_POST['email_randonneur'],$_POST['password_randonneur'],$_admin,$_POST['modify']);
}

if(isset($_POST['add_entry'])) {
    $_admin = (isset($_POST['_admin']))? '1': '0';

    $query = "INSERT INTO `randonneur` (`nom_randonneur`,`prenom_randonneur`,`email_randonneur`,`password_randonneur`) VALUES (?,?,?,?)";
    doIt4($query,$_POST['nom_randonneur'],$_POST['prenom_randonneur'],$_POST['email_randonneur'],$_POST['password_randonneur']);
}



if(isset($_POST['add'])) {
    $content = "<form action='' method='post' id='edit_form'>
                    <label for='nom_randonneur'>Nom</label>
                    <input type='text' name='nom_randonneur' id='nom_randonneur'>         
                    
                    <label for='prenom_randonneur'>Prenom</label>
                    <input type='text' name='prenom_randonneur' id='prenom_randonneur'>

                    <label for='email_randonneur'>Email</label>
                    <input type='email' name='email_randonneur' id='email_randonneur'>

                    <label for='password_randonneur'>password</label>
                    <input type='password' name='password_randonneur' id='password_randonneur'>
    
                    <button type ='submit' class='button' name='add_entry'>Ajouter</button>
                    <button type ='submit' class='button'>Annuler</button>
                </form>";
} elseif(isset($_POST['edit'])) {
        $query = "SELECT * FROM `randonneur` WHERE id_randonneur = ?";
        $reponse = doIt2($query,$_POST['edit'],'');
        
        if ($donnees = $reponse->fetch()) {
            $content = "
                <form action='' method='post' id='edit_form'>
                    <label for='nom_randonneur'>Nom</label>
                    <input type='text' name='nom_randonneur' id='nom_randonneur' value='".$donnees['nom_randonneur']."'>         
                    
                    <label for='prenom_randonneur'>Prenom</label>
                    <input type='text' name='prenom_randonneur' id='prenom_randonneur' value='".$donnees['prenom_randonneur']."'>

                    <label for='email_randonneur'>Email</label>
                    <input type='email' name='email_randonneur' id='email_randonneur' value='".$donnees['email_randonneur']."'>

                    <label for='password_randonneur'>password</label>
                    <input type='password' name='password_randonneur' id='password_randonneur' value='".$donnees['password_randonneur']."'>
    
                    <label for='admin'>Admin</label>
                    <input type='checkbox' name='_admin[]' value='1' id='admin'";
                    $content .=  ($donnees['_admin']==1)? "checked='checked'> ":">";
                    
                    
                    $content .=  "
                    <button type ='submit' class='button' name='modify' value='".$donnees['id_randonneur']."'>Modifier</button>
                    <button type ='submit' class='button'>Annuler</button>
                </form>";
        }
    } else {

        $content = "
            <form action='' method='post'>
            <button type ='submit' class='button' name='add'>Ajout</button>
            </form>
            <table>
                <thead>
                <tr>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Email</th>
                <th>Password</th>
                <th>Admin</th>
                <th>Action</th>
                </tr>
                </thead>
                <tbody>
                ";

                $query = "SELECT * FROM `randonneur` ORDER BY `nom_randonneur`";

                $reponse = doIt2($query,'','');
                
                while ($donnees = $reponse->fetch()) {
                    $content .= "
                    <tr>
                        <th>".$donnees['nom_randonneur']." </th>
                        <th>".$donnees['prenom_randonneur']." </th>
                        <th>".$donnees['email_randonneur']." </th>
                        <th>".$donnees['password_randonneur']." </th>
                        <th>".$donnees['_admin']." </th>
                        <th>
                            <form action='' method='post'>
                                <button type ='submit' class='button' name='edit' value='".$donnees['id_randonneur']."'>Editer</button>
                                <button type ='submit' class='button' name='delete' value='".$donnees['id_randonneur']."'>Supprimer</button>
                            </form>
                        </th>
                    </tr>
                    ";
                }
                $content .= "</table>";


        }

echo $content;
include 'footer.php';