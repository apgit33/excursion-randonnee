<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$nom = (isset($_POST['nom_guide']) ? trim($_POST['nom_guide']):"");
$telephone = (isset($_POST['telephone']) ? trim($_POST['telephone']):"");


if(!preg_match('^[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])+))*$^',$nom)) {
    $erreurs[] = 'Nom incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[] =  "Nom entre 3 et 16 caractère";
}

if(!preg_match("#^0[6-7]([-. ]?[0-9]{2}){4}$#",$telephone)) {
    $erreurs[] = 'Téléphone incorrect';
}

array_filter($erreurs);
$array= (!empty($erreurs)) ? implode(',', $erreurs) : 0;

if (empty($array)) {
    $query = "INSERT INTO `nc_guide` (`g_nom`,`g_telephone`) VALUES (?,?)";
    executeSQL($query,array($nom,$telephone));
    $validation = true;
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
