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

function executeSQL($query,$array){
    
    $co = connect();		
    $sth = $co->prepare($query);
    foreach($array as $key => &$val){
        $sth->bindParam($key+1,$val);
    }
    $sth->execute();

    return $sth;
}