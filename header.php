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

    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-item" href="index.php">
                <img src="./img/nc64x64.png" width="64" height="64">
                <p>Natural Coach</p>
            </a>
            <div class="navbar-end">
                <div class="navbar-item">
                    <div>Welcome <a href='index.php'><?=$_SESSION['user_firstname'];?></a></div>
                </div>
            </div>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id='navbarBasicExample' class="navbar-menu">
            <div class="navbar-start">
                <a href='index.php?action=logout' class="navbar-item">Logout</a>
                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-item">Manage Hikers</p>
                    <div class="navbar-dropdown">
                        <a href='randonneurs.php?action=list' class="navbar-item">Hikers list</a>
                        <a href='randonneurs.php?action=add' class="navbar-item">New Hiker</a>
                        <a href='bookingr.php?action=list' class="navbar-item">Booking Hikers</a>
                    </div>
                </div>   
                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-item">Manage Guides</p>
                    <div class="navbar-dropdown">
                        <a  href='guide.php?action=list' class="navbar-item">Guide list</a>
                        <a href='guide.php?action=add' class="navbar-item">New guide</a>
                        <a href='bookingg.php?action=list' class="navbar-item">Booking Guides</a>
                    </div>
                </div>    
                <div class="navbar-item has-dropdown is-hoverable">
                    <p class="navbar-item">Manage Excursion</p>
                    <div class="navbar-dropdown">
                        <a href='excursion.php?action=list' class="navbar-item">Excursions list</a>
                        <a href='excursion.php?action=add' class="navbar-item">New excursion</a>
                        <a href='bookinge.php?action=list' class="navbar-item">Booking excursions</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
            <div id='content' class='columns'>
                <div class='column is-2 is-hidden-mobile'>
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