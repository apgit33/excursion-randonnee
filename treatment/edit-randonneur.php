<?php
session_start();
require_once '../bdd.php';
$validation = false;
$co=false;
$erreurs[] ='';
$nom = (isset($_POST['nom_randonneur']) ? trim($_POST['nom_randonneur']):"");
$prenom = (isset($_POST['prenom_randonneur']) ? trim($_POST['prenom_randonneur']):"");
$email = (isset($_POST['email_randonneur']) ? trim($_POST['email_randonneur']):"");
$pwd = (isset($_POST['password_randonneur']) ? $_POST['password_randonneur']:"");

if(!preg_match('^[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])+))*$^',$nom)) {
    $erreurs[]['nom'] = 'Nom incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[]['nom'] =  "Nom entre 3 et 16 caractère";
}

if(!preg_match("#^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$#iu",$prenom)) {
    $erreurs[]['prenom'] = 'Prenom incorrect';
}elseif(strlen($prenom) < 3 || strlen($prenom) > 16) {
    $erreurs[]['prenom'] =  "Prénom entre 3 et 16 caractère";
}

if  (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {  
    $erreurs[]['email'] = "L'email n'est pas valide";
} 

if  (!preg_match("~[A-Z]~", $pwd)) {  $erreurs[]['password'] = "Le password doit avoir au moins une Majuscule";}
if  (!preg_match("~[0-9]~", $pwd)) {  $erreurs[]['password'] = "Le password doit avoir au moins un chiffre";}

$query = "SELECT r_id FROM `nc_randonneur` WHERE r_email = ?";
$reponse = executeSQL($query,array($email));
if ($donnee = $reponse->fetch()) {
    if (isset($_POST['id'])){$co = false;
        if($donnee['r_id'] != $_POST['id'])  {
            $erreurs[]['email'] = "Email already on use";
        }
    }else{ 
        $co = true;

        if($donnee['r_id'] != $_SESSION['id'])  {
            $erreurs[]['email'] = "Email already on use";
        }
    }
}

if (!isset($erreurs[1])) {
    $query = "UPDATE `nc_randonneur` SET `r_nom` = ?,`r_prenom` = ?,`r_email` = ?,`r_password` = ? WHERE `r_id` = ?";
    if ($co == false) {
        executeSQL($query,array($nom,$prenom,$email,$pwd,$_POST['id']));
    }else {
        executeSQL($query,array($nom,$prenom,$email,$pwd,$_SESSION['id']));
        $_SESSION['user_lastname'] = $nom;
        $_SESSION['user_firstname'] = $prenom;
    }
    $validation = true;
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
