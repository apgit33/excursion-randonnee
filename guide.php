<?php
include 'header.php';

if(isset($_POST['delete'])) {
    $query = "DELETE FROM `guide` WHERE numero_guide = ?";
    $reponse = doIt2($query,$_POST['delete'],'');
}


if(isset($_POST['modify'])) {

    $query = "UPDATE `guide` SET `nom_guide` = ?,`telephone` = ? WHERE `numero_guide` = ?";
    doIt4($query,$_POST['nom_guide'],$_POST['telephone'],$_POST['modify'],'');
}

if(isset($_POST['add_entry'])) {
    $query = "INSERT INTO `guide` (`nom_guide`,`telephone`) VALUES (?,?)";
    doIt2($query,$_POST['nom_guide'],$_POST['telephone']);
}

if(isset($_POST['add'])) {
    $content = "<form action='' method='post' id='edit_form'>
                    <label for='nom_guide'>Nom</label>
                    <input type='text' name='nom_guide' id='nom_guide'>         
                    
                    <label for='telephone'>Telephone</label>
                    <input type='text' name='telephone' id='telephone'>

                    <button type ='submit' class='button' name='add_entry'>Ajouter</button>
                    <button type ='submit' class='button'>Annuler</button>
                </form>";
} elseif(isset($_POST['edit'])) {

    $query = "SELECT * FROM `guide` WHERE numero_guide = ?";
    $reponse = doIt2($query,$_POST['edit'],'');
    
    if ($donnees = $reponse->fetch()) {
        $content = "
            <form action='' method='post' id='edit_form'>
                <label for='nom_guide'>Nom</label>
                <input type='text' name='nom_guide' id='nom_guide' value='".$donnees['nom_guide']."'>         
                
                <label for='telephone'>telephone</label>
                <input type='text' name='telephone' id='telephone' value='".$donnees['telephone']."'>";
                $content .=  "
                <button type ='submit' class='button' name='modify' value='".$donnees['numero_guide']."'>Modifier</button>
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
                <th>Numéro</th>
                <th>Nom</th>
                <th>Telephone</th>
                </tr>
                </thead>
                <tbody>
                ";

                $query = "SELECT * FROM `guide` ORDER BY `nom_guide`";

                $reponse = doIt2($query,'','');
                
                while ($donnees = $reponse->fetch()) {
                    $id=$donnees['numero_guide'];
                    $content .= "
                    <tr>
                        <th>".$id." </th>
                        <th>".$donnees['nom_guide']." </th>
                        <th>".$donnees['telephone']." </th>
                        <th>
                            <form action='' method='post'>
                                <button type ='submit' class='button' name='edit' value='$id'>Editer</button>
                                <button type ='submit' class='button' name='delete' value='$id'>Supprimer</button>
                            </form>
                        </th>
                    </tr>
                    ";
                }
                $content .= "</table>";
            }

echo $content;

include 'footer.php';