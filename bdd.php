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

function takeAll($query,$val1,$val2) {
    try {
        $co = connect();		
        $sth = $co->prepare($query);
        ($val1!='') ? $sth->bindParam(1,$val1):'';
        ($val2!='') ? $sth->bindParam(2,$val2):'';
        $sth->execute();
    } catch(PDOException $e) {
        $msg = 'ERREUR PDO dans ' . $e->getFile() . ' Ligne.' . $e->getLine() . ' : <br>' . $e->getMessage();
        die($msg);
    }

    return $sth;
}