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

