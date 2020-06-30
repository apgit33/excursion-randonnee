<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$nom = (isset($_POST['nom_excursion']) ? trim($_POST['nom_excursion']):"");
$pd = (isset($_POST['point_depart_excursion']) ? trim($_POST['point_depart_excursion']):"");
$pa = (isset($_POST['point_arrive_excursion']) ? trim($_POST['point_arrive_excursion']):"");
$tarif = (isset($_POST['tarif_excursion']) ? trim($_POST['tarif_excursion']):"");
$max = (isset($_POST['randonneurs_max_excursion']) ? trim($_POST['randonneurs_max_excursion']):"");
$dp = (isset($_POST['date_depart_excursion']) ? trim($_POST['date_depart_excursion']):"");
$da = (isset($_POST['date_arrivee_excursion']) ? trim($_POST['date_arrivee_excursion']):"");


if(!preg_match('^[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z])+))*$^',$nom)) {
    $erreurs[]['nom'] = 'Name incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[]['nom'] =  "Name size between 3 & 16 required";
}

if(!preg_match("/^\d+(?:\.\d+)?$/",$tarif)) {$erreurs[]['tarif'] = 'Price incorrect';}
if(!preg_match("/^\d+(?:\.\d+)?$/",$max)) {$erreurs[]['max'] = 'Number incorrect';}

if((!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dp)) || ($dp<date('Y-m-d')) ) {
    $erreurs[]['dp'] = 'Starting date invalid';
}
if((!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$da)) || ($da<date('Y-m-d')) || ($da<$dp) ) {
    $erreurs[]['da'] = "End date invalid";
}
if ($pd=='' || $pa=='') {
    $erreurs[]['point'] = "Starting or End point required";
}

if (!isset($erreurs[1])) {
    $validation = true;
    $query = "UPDATE `nc_excursion` SET e_nom = ?, e_point_depart = ?, e_point_arrivee = ?,e_date_depart = ?,e_date_arrivee = ?,e_tarif = ?,e_randonneurs_max = ?  WHERE `e_id` = ?";
    executeSQL($query,array($nom,$pd,$pa,$dp,$da,$tarif,$max,$_POST['id']));
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
