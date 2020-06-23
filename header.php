<?php

require_once 'bdd.php';
require_once 'function.php';

echo "
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='icon' href='favicon.ico'/>
        <title>Natural Coach</title>
        <link rel='stylesheet' href='default.css'>
    </head>
    <body>
        <header>
            <div class='container d-flex'>
                <nav class='d-flex jc-end ai'>
                    <ul id='menu' class='d-flex jc-around ai'>
                        <li><a href='index.php'>Home</a></li>
                        <li><a href='randonneurs.php'>randonneurs</a></li>
                        <li><a href='guide.php'>guide</a></li>
                        <li><a href='excursion.php'>excursion</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class='container'>
"
?>