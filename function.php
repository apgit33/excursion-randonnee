<?php

   
function date_outil($date,$nombre_jour) {
 
    $year = substr($date, 0, -6);   
    $month = substr($date, -5, -3);   
    $day = substr($date, -2);   
 
    // récupère la date du jour
    $date_string = mktime(0,0,0,$month,$day,$year);
 
    // Supprime les jours
    $timestamp = $date_string - ($nombre_jour * 86400);
    return date("Y-m-d", $timestamp);
}
function getURI(){
    $adresse = $_SERVER['PHP_SELF'];
    $i = 0;
    foreach($_GET as $cle => $valeur){
        $adresse .= ($i == 0 ? '?' : '&').$cle.($valeur ? '='.$valeur : '');
        $i++;
    }
    return $adresse;
}
function affichePagination($cPage,$nbPage) {

    if ($nbPage != 1) {
        $content = "
        <nav class='pagination is-centered' role='navigation' aria-label='pagination'>
            <form action=\"?action=''\" method='get'>
                    <button class='button pagination-previous' type='submit' name='page' value='".($cPage-1);
                    $content .= ($cPage==1)?"' disabled title='This is the first page'":"'";
                    $content .= ">Previous</button>
            </form>                 
            
            <form action='?' method='get'>
                <button class='button pagination-next' type='submit' name='page' value='".($cPage+1);
                $content .= ($cPage==$nbPage)?"' disabled title='This is the last page'":"'";
        
        return  $content .=">Next</button></form></nav>";
    } return "";
}