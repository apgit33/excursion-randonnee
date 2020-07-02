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
                
                <div class='d-flex jc-between header'>
                    <div class='d-flex jc-between d-centered'>
                        <img src="./img/nc64x64.png" width="64" height="64" class='mr-r10'>
                        <p>Natural Coach</p>
                    </div>

                    <p>Welcome <a href='index.php'><?=$_SESSION['user_firstname'];?></a></p>
                </div>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="menu-v">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>

            <div id='content' class='columns'>
                <div class='is-hidden-mobile' id='menu-v'>
                 <aside class='menu '>
                    <p class='menu-label'>Home</p>
                    <ul class='menu-list'>
                        <li><a href='index.php'>Dashboard</a></li>
                        <li><a href='index.php?action=logout'>Deconnection</a></li>
                    </ul>
            <?php if(isset($_SESSION['admin']) && $_SESSION['admin']===true){?>
                    <p class='menu-label'>Administration</p>
                    <ul class='menu-list'>
                        <li>Manage Hikers
                            <ul>
                                <li><a href='randonneurs.php?action=list'>Hikers list</a></li>
                                <li><a href='randonneurs.php?action=add'>New Hiker</a></li>
                                <li><a href='bookingr.php?action=list'>Booking Hikers</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class='menu-list'>
                        <li>Manage Guides
                            <ul>
                                <li><a href='guide.php?action=list'>Guide list</a></li>
                                <li><a href='guide.php?action=add'>New guide</a></li>
                                <li><a href='bookingg.php?action=list'>Booking Guides</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class='menu-list'>
                        <li>Manage Excursion
                            <ul>
                                <li><a href='excursion.php?action=list'>Excursions list</a></li>
                                <li><a href='excursion.php?action=add'>New excursion</a></li>
                                <li><a href='bookinge.php?action=list'>Booking excursions</a></li>
                            </ul>
                        </li>
                    </ul>
        <?php }else {?>
            <p class='menu-label'>Gestion</p>
                    <ul class='menu-list'>
                        <li>Profile
                            <ul>
                                <li><a href='dashboard.php?action=edit'>Edit your profile</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class='menu-list'>
                        <li>Manage Excursions
                            <ul>
                                <li><a href='excursion_list.php?action=list'>Your Excursions</a></li>
                                <li><a href='excursion_list.php?action=add'>New Excursion</a></li>
                            </ul>
                        </li>
                    </ul>
            <?php }?>

                </aside>
            </div>
                <?php
                }
                ?>