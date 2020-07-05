<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$nom = (isset($_POST['nom_randonneur']) ? trim($_POST['nom_randonneur']):"");
$prenom = (isset($_POST['prenom_randonneur']) ? trim($_POST['prenom_randonneur']):"");
$email = (isset($_POST['email_randonneur']) ? trim($_POST['email_randonneur']):"");
$pwd = (isset($_POST['password_randonneur']) ? $_POST['password_randonneur']:"");

if(!preg_match('^[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])+))*$^',$nom)) {
    $erreurs[]['nom'] = 'First name incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[]['nom'] =  "First name size between 3 & 16 required";
}

if(!preg_match("#^([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+([-]([a-zàáâäçèéêëìíîïñòóôöùúûü]+(( |')[a-zàáâäçèéêëìíîïñòóôöùúûü]+)*)+)*$#iu",$prenom)) {
    $erreurs[]['prenom'] = 'Last name incorrect';
}elseif(strlen($prenom) < 3 || strlen($prenom) > 16) {
    $erreurs[]['prenom'] =  "Last name size between 3 & 16 required";
}

if  (!filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) || $email='admin@admin.fr') {  
    $erreurs[]['email'] = "Email invalid";
} 

if  (!preg_match("~[A-Z]~", $pwd)) {  $erreurs[]['password'] = "At least one capital";}
if  (!preg_match("~[0-9]~", $pwd)) {  $erreurs[]['password'] = "At least one number";}

$query = "SELECT r_id FROM `nc_randonneur` WHERE r_email = ?";
$reponse = executeSQL($query,array($email));
if ($donnee = $reponse->fetch()) {
    $erreurs[]['email'] = "Email already on use";
}

if (!isset($erreurs[1])) {
    $options = ['cost' => 9];
    $query = "INSERT INTO `nc_randonneur` (`r_nom`,`r_prenom`,`r_email`,`r_password`) VALUES (?,?,?,?)";

    executeSQL($query,array($nom,$prenom,$email,password_hash($pwd,PASSWORD_BCRYPT,$options)));
    $validation = true;
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
