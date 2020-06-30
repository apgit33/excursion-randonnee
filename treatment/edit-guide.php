<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$nom = (isset($_POST['nom_guide']) ? trim($_POST['nom_guide']):"");
$telephone = (isset($_POST['telephone']) ? trim($_POST['telephone']):"");

if(!preg_match('^[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])+))*$^',$nom)) {
    $erreurs[]['nom'] = 'Nom incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[]['nom'] =  "Nom entre 3 et 16 caractère";
}

if(!preg_match("#^0[6-7]([-. ]?[0-9]{2}){4}$#",$telephone)) {
    $erreurs[]['phone'] = 'Téléphone incorrect';
}

$query = "SELECT g_telephone FROM `nc_guide` WHERE g_telephone = ?";
$reponse = executeSQL($query,array($telephone));
if ($donnee = $reponse->fetch()) {
    if($donnee['g_telephone']!=$_POST['g_num']) {
        $erreurs[]['phone'] = "Phone number already on use";
    }
}

if (!isset($erreurs[1])) {
    $query = "UPDATE `nc_guide` SET `g_nom` = ?,`g_telephone` = ? WHERE `g_numero` = ?";
    executeSQL($query,array($nom,$telephone,$_POST['g_num']));
    $validation = true;
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));