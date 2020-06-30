<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$max ='';

$query = "SELECT (SELECT `e_randonneurs_max` FROM `nc_excursion` WHERE `e_id`= ?) - count(r_id) as nombre_restant FROM `nc_randonneur` INNER JOIN nc_booking ON r_id = b_r_id WHERE b_e_id = ? ";
$reponse = executeSQL($query,array($_POST['id'],$_POST['id']));
while($donnees = $reponse->fetch()) {
    $max = $donnees['nombre_restant'];
}

if($max<=0) {
    $erreurs = 'Sorry, there are no more places available for this excursion';
}

array_filter($erreurs);
$array= (!empty($erreurs)) ? implode(',', $erreurs) : 0;

if (empty($array)) {
    $validation = true;
    $query = "INSERT INTO nc_booking VALUE (?,?)";
    executeSQL($query,array($_POST['r_id'],$_POST['id']));    
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
