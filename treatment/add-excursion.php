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
    $erreurs[]['nom'] = 'Nom incorrect';
}elseif(strlen($nom) < 3 || strlen($nom) > 16) {
    $erreurs[]['nom'] =  "Nom entre 3 et 16 caractère";
}

if(!preg_match("/^\d+(?:\.\d+)?$/",$tarif)) {$erreurs[]['tarif'] = 'Tarif incorrect';}
if(!preg_match("/^\d+(?:\.\d+)?$/",$max)) {$erreurs[]['max'] = 'Nombre de randonneurs maximum incorrect';}

if((!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$dp)) || ($dp<date('Y-m-d')) ) {
    $erreurs[]['dp'] = 'Date de départ invalide';
}
if((!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$da)) || ($da<date('Y-m-d')) || ($da<$dp) ) {
    $erreurs[]['da'] = "Date d'arrivée invalide";
}
if ($pd=='' || $pa=='') {
    $erreurs[]['point'] = "Point de départ ou d'arrivée incorrect";
}


if (!isset($erreurs[1])) {
    $validation = true;
    $query = "INSERT INTO `nc_excursion`( `e_nom`, `e_point_depart`, `e_point_arrivee`, `e_date_depart`, `e_date_arrivee`, `e_tarif`, `e_randonneurs_max`) VALUES (?,?,?,?,?,?,?)";
    $array = array($nom,$pd,$pa,$dp,$da,$tarif,$max);
    $co = connect();		
    $sth = $co->prepare($query);
    foreach($array as $key => &$val){
        $sth->bindParam($key+1,$val);
    }
    $sth->execute();

    if(isset($_POST['guide_ids'])) {
        $lastId = $co->lastInsertId();
        foreach ($_POST['guide_ids'] as $guide) {
            $query ="INSERT INTO nc_guidemeneexcursion VALUES (?,?)";
            executeSQL($query,array($lastId,$guide));
        }
    }
}

echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));
