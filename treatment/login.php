<?php
session_start();
require_once '../bdd.php';
$validation = false;
$erreurs[] ='';
$email = (isset($_POST['email']) ? $_POST['email']:"");
$password = (isset($_POST['password']) ? $_POST['password']:"");

if ($email !='' && $password != '') {
    $query = "SELECT u_admin FROM `nc_user` WHERE u_email = ? AND u_password = ?";
    $reponse = executeSQL($query,array($email,$password));
    if ($donnees = $reponse->fetch()) {
        $_SESSION['login'] = true;
        $_SESSION['user_firstname'] = 'Admin';
        $_SESSION['admin'] = true;
        $validation = true;
    }else{
        $query = "SELECT r_id, r_nom, r_prenom, r_email, r_password FROM `nc_randonneur` WHERE r_email = ? AND r_password = ?";
    		
        $reponse = executeSQL($query,array($email,$password));
    
        if ($donnees = $reponse->fetch()) {
            $validation = true;
            $_SESSION['user_firstname'] = $donnees['r_nom'];
            $_SESSION['user_lastname'] = $donnees['r_prenom'];
            $_SESSION['login'] = true;
            $_SESSION['id'] = $donnees['r_id'];
        }else{
            $erreurs[] ='Wrong login or password';
        }
    }
}else{
    $erreurs[] = 'Login or Password required';
}
echo json_encode(array('validation' => $validation,'erreurs' => $erreurs));