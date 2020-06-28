<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$nom = (isset($_POST['nom_randonneur']) ? trim($_POST['nom_randonneur']):"");
$prenom = (isset($_POST['prenom_randonneur']) ? trim($_POST['prenom_randonneur']):"");
$email = (isset($_POST['email_randonneur']) ? $_POST['email_randonneur']:"");
$pwd = (isset($_POST['password_randonneur']) ? $_POST['password_randonneur']:"");

if(!preg_match('^[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])+))*$^',$nom)) {
    $erreurs[] = 'Nom incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[] =  "Nom entre 3 et 16 caractère";
}

if(!preg_match("#^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$#iu",$prenom)) {
    $erreurs[] = 'Prenom incorrect';
}elseif(strlen($prenom) < 3 || strlen($prenom) > 16) {
    $erreurs[] =  "Prénom entre 3 et 16 caractère";

}

if  (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {  
    $erreurs[] = "L'email n'est pas valide";
} 

if  (!preg_match("~[A-Z]~", $pwd)) {  $erreurs[] = "Le password doit avoir au moins une Majuscule";

}
if  (!preg_match("~[0-9]~", $pwd)) {  $erreurs[] = "Le password doit avoir au moins un chiffre";

}

array_filter($erreurs);
$array= (!empty($erreurs)) ? implode(',', $erreurs) : 0;

if (empty($array)) {
    $query = "INSERT INTO `nc_randonneur` (`r_nom`,`r_prenom`,`r_email`,`r_password`) VALUES (?,?,?,?)";
    executeSQL($query,array($_POST['nom_randonneur'],$_POST['prenom_randonneur'],$_POST['email_randonneur'],$_POST['password_randonneur']));
    $validation = true;
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
