<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel="icon" type="image/png" href="favicon.png" />
        <title>Natural Coach</title>
        <link rel='stylesheet' href='https://cdn.materialdesignicons.com/5.3.45/css/materialdesignicons.min.css'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bulma@0.9.0/css/bulma.min.css'>
        <link rel='stylesheet' href='./css/default.css'>
    </head>
    <body>
        <main> 
            <div class='container'>
            <?php if(isset($_SESSION['login']) && $_SESSION['login']===true){?>
       
                <div class='d-flex d-centered jc-between'>
                    <div class='d-flex d-centered'>
                        <figure class="image is-64x64">
                            <img src="./img/nc64x64.png">
                        </figure>
                        <h1>Natural Coach</h1>
                    </div>
                    <div>Welcome <a href='index.php'><?=$_SESSION['user_lastname']." ".$_SESSION['user_firstname'];?></a></div>
            </div>
            <div class='columns'>
                <div class='column is-2 '>
                 <aside class='menu'>
                    <p class='menu-label'>
                        Home
                    </p>
                    <ul class='menu-list'>
                        <li><a href='index.php'>Dashboard</a></li>
                        <li><a href='index.php?action=logout'>Deconnection</a></li>
                    </ul>
            <?php if(isset($_SESSION['admin']) && $_SESSION['admin']===true){?>

                    <p class='menu-label'>
                        Administration
                    </p>
                    <ul class='menu-list'>
                        <li>
                            Manage Randonneurs
                            <ul>
                                <li><a href='randonneurs.php?action=list'>Liste randonneurs</a></li>
                                <li><a href='randonneurs.php?action=add'>New randonneur</a></li>
                                <li><a href='bookingr.php?action=list'>Reservation randonneurs</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class='menu-list'>
                        <li>
                            Manage Guides
                            <ul>
                                <li><a href='guide.php?action=list'>Liste guides</a></li>
                                <li><a href='guide.php?action=add'>New guide</a></li>
                                <li><a href='bookingg.php?action=list'>Reservation guides</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class='menu-list'>
                        <li>
                            Manage Excursion
                            <ul>
                                <li><a href='excursion.php?action=list'>Liste excursions</a></li>
                                <li><a href='excursion.php?action=add'>New excursion</a></li>
                                <li><a href='bookinge.php?action=list'>Reservation excursions</a></li>
                            </ul>
                        </li>
                    </ul>
        <?php }else {?>
            <p class='menu-label'>
                        Gestion
                    </p>
                    <ul class='menu-list'>
                        <li>
                            Profile
                            <ul>
                                <li><a href='excursion_list.php?action=edit'>Edit your profile</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class='menu-list'>
                        <li>
                            Manage Excursions
                            <ul>
                                <li><a href='excursion_list.php?action=list'>Your Excursions</a></li>
                                <li><a href='excursion_list.php?action=add'>Ur Excursions</a></li>
                            </ul>
                        </li>
                    </ul>
            <?php }?>

                </aside>
            </div>
                <?php
                }
                ?>