<?php
require_once 'constant.php';



function connect() {
		
    try {
        $bdd  = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', DB_USERNAME, DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));	
    } catch(PDOException $e) {
        $msg = 'ERREUR PDO dans ' . $e->getFile() . ' Ligne.' . $e->getLine() . ' : <br>' . $e->getMessage();
        die($msg);
    }
    return $bdd;

}

function doIt2($query,$val1,$val2) {

    $co = connect();		
    $sth = $co->prepare($query);
    ($val1!='') ? $sth->bindParam(1,$val1):'';
    ($val2!='') ? $sth->bindParam(2,$val2):'';
    $sth->execute();

    return $sth;
}

function doIt6($query,$val1,$val2,$val3,$val4,$val5,$val6) {

    $co = connect();		
    $sth = $co->prepare($query);
    $sth->bindParam(1,$val1);
    $sth->bindParam(2,$val2);
    $sth->bindParam(3,$val3);
    $sth->bindParam(4,$val4);
    $sth->bindParam(5,$val5);
    $sth->bindParam(6,$val6);
    $sth->execute();

    return $sth;
}

function doIt4($query,$val1,$val2,$val3,$val4){
    $co = connect();		
    $sth = $co->prepare($query);
    $sth->bindParam(1,$val1);
    $sth->bindParam(2,$val2);
    $sth->bindParam(3,$val3);
    $sth->bindParam(4,$val4);
    $sth->execute();

    return $sth;
}