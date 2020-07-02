<?php
session_start();
if (!isset($_SESSION["admin"]) || $_SESSION["admin"]===false){
    header('Location: index.php');
    exit;
}else{
    $action = (isset($_GET['action']))? $_GET['action']:'list';
    require_once 'bdd.php';
    require_once 'function.php';
    include 'header.php';
    $region ='';

    if(isset($_POST['delete'])) {
        $query = "DELETE FROM `nc_excursion` WHERE e_id = ?";
        executeSQL($query,array($_POST['delete']));     
        $query = "DELETE FROM `nc_guidemeneexcursion` WHERE ge_e_id = ?";
        executeSQL($query,array($_POST['delete']));     
        $query = "DELETE FROM `nc_booking` WHERE b_e_id = ?";
        executeSQL($query,array($_POST['delete']));     
    }

    if($action =='add') {
        $reponse = executeSQL("SELECT DISTINCT `d_nom` FROM `nc_district` ORDER BY `d_nom`",array());
        while ($donnees = $reponse->fetch()){
            $region .= "<option value ='".$donnees['d_nom']."'>".$donnees['d_nom']."</option>";
        }
        $title = "<h2 class='title is-2 has-text-centered'>Add excursion</h2>";
        $content = "   
        <div class='column is-offset-one-quarter'>
        <div class='column is-half '>$title
        <form action='' method='post' id='add_form'>
            <div class='field'>
                <label for='nom_excursion' class='label'>Name :</label>
                <div class='control'>
                    <input type='text' name='nom_excursion' id='nom_excursion' class='input' required>         
                </div>
                <div id='checkfirstname' class='verif'></div>
            </div>
            <div class='field'>
                <label for='point_depart_excursion' class='label'>Starting point :</label>
                <div class='control'>
                    <div class='select'>
                        <select name='point_depart_excursion' id='point_depart_excursion' required>$region</select>
                    </div>
                </div>
            </div>
            <div class='field'>
                <label for='point_arrive_excursion' class='label'>End point :</label>
                <div class='control'>
                    <div class='select'>
                        <select name='point_arrive_excursion' id='point_arrive_excursion' required>$region</select>
                    </div>
                </div>
            </div>
            <div class='field'>
                <label for='tarif_excursion' class='label'>Price €:</label>
                <div class='control'>
                    <input class='input' type='number' name='tarif_excursion' id='tarif_excursion' required>
                </div>
                <div id='checktarif' class='verif'></div>
            </div>
            <div class='field'>
                <label for='randonneurs_max_excursion' class='label'>Hikers max :</label>
                <div class='control'>
                    <input class='input' type='number' name='randonneurs_max_excursion' id='randonneurs_max_excursion' required>     
                </div>
                <div id='checkmax' class='verif'></div>
            </div>
            <div class='field'>
                <label for='date_depart_excursion' class='label'>Start :</label>
                <div class='control'>
                    <input class='input' type='date' name='date_depart_excursion' id='date_depart_excursion' required>  
                </div>
                <div id='checkdp' class='verif'></div>
            </div>
            <div class='field'>
                <label for='date_arrivee_excursion' class='label'>End :</label>
                <div class='control'>
                    <input class='input' type='date' name='date_arrivee_excursion' id='date_arrivee_excursion' required>  
                </div>
                <div id='checkda' class='verif'></div>
            </div>
            <div class='field'>
                <fieldset>
                    <legend class='label'>Guides :</legend>";

                $query = $query = "SELECT DISTINCT  g_numero, g_nom from nc_guide ";
                $reponse2 =executeSQL($query,array());
                $guides = $reponse2->fetchAll();

                foreach($guides as $guide){
                    $content.= "<label class='checkbox'>
                    <input type='checkbox' name='guide_ids[]' value='".$guide['g_numero']."'>
                    ".$guide['g_nom']."</label>";
                }

                $content .="
                </fieldset>
            </div>
            <div class='field is-grouped'>
                <div class='control'>
                    <button type ='submit' class='button is-success' name='add_entry'>Create</button>
                </div>
                <div class='control'>
                    <a href='excursion.php' class='button is-danger' name='cancel'>Cancel</a>
                </div>
            </div>
            </form>
            <div id='verif'></div>";
    } elseif(isset($_POST['edit'])) {

        $query = "SELECT e_nom, e_point_depart, e_point_arrivee, e_tarif, e_randonneurs_max, e_date_depart, e_date_arrivee FROM `nc_excursion` WHERE e_id = ?";
        $reponse = executeSQL($query,array($_POST['edit']));
        if ($donnees = $reponse->fetch()) {
            $title = "<h2 class='title is-2'>Edit of excursion n ° ".$_POST['edit']."</h2>";
            $content = "   
            <div class='column is-offset-one-quarter'>
            <div class='column is-half '>$title
            <form action='' method='post' id='edit_form'>
                <div class='field'>
                    <label for='nom_excursion' class='label'>Name :</label>
                    <div class='control'>
                        <input class='input' type='text' name='nom_excursion' id='nom_excursion' value='".$donnees['e_nom']."' required>         
                    </div>
                    <div id='checkfirstname' class='verif'></div>
                </div>
                <div class='field'>
                    <label for='point_depart_excursion' class='label'>Starting point :</label>
                    <div class='control'>
                        <div class='select'>
                            <select name='point_depart_excursion' id='point_depart_excursion' required>    
                    ";
                $reponse = executeSQL("SELECT DISTINCT `d_nom` FROM `nc_district` ORDER BY `d_nom`",array());
                while ($donnees2 = $reponse->fetch()){
                    $content .= "<option value ='".$donnees2['d_nom']."'";
                    if ($donnees2['d_nom'] == $donnees['e_point_depart']) {
                        $content .= ' selected';
                    }
                    $content .= ">".$donnees2['d_nom']."</option>";
                }
                $content .= "</select>
                        </div>
                    </div>
                </div>
                <div class='field'>
                    <label for='point_arrive_excursion' class='label'>End point :</label>
                    <div class='control'>
                        <div class='select'>
                            <select name='point_arrive_excursion' id='point_arrive_excursion' required>";
                $reponse = executeSQL("SELECT DISTINCT `d_nom` FROM `nc_district` ORDER BY `d_nom`",array());
                while ($donnees2 = $reponse->fetch()){
                    $content .= "<option value ='".$donnees2['d_nom']."'";
                    if ($donnees2['d_nom'] == $donnees['e_point_arrivee']) {
                        $content .= ' selected';
                    }
                    $content .= ">".$donnees2['d_nom']."</option>";
                }
                $content .= "</select>       
                        </div>
                    </div>
                </div>
                <div class='field'>
                    <label for='tarif_excursion' class='label'>Price € :</label>
                    <div class='control'>
                        <input class='input' type='number' name='tarif_excursion' id='tarif_excursion' value='".$donnees['e_tarif']."' required>
                    </div>
                    <div id='checktarif' class='verif'></div>
                </div>
                <div class='field'>
                    <label for='randonneurs_max_excursion' class='label'>Hikers max :</label>
                    <div class='control'>
                        <input class='input' type='number' name='randonneurs_max_excursion' id='randonneurs_max_excursion' value='".$donnees['e_randonneurs_max']."' required>     
                        </div>
                        <div id='checkmax' class='verif'></div>
                    </div>
                <div class='field'>
                    <label for='date_depart_excursion' class='label'>Start :</label>
                    <div class='control'>
                        <input class='input' type='date' name='date_depart_excursion' id='date_depart_excursion' value='".$donnees['e_date_depart']."' required>
                        </div>
                        <div id='checkdp' class='verif'></div>
                    </div>
                <div class='field'>
                    <label for='date_arrivee_excursion' class='label'>End :</label>
                    <div class='control'>
                        <input class='input' type='date' name='date_arrivee_excursion' id='date_arrivee_excursion' value='".$donnees['e_date_arrivee']."' required>
                        </div>
                        <div id='checkda' class='verif'></div>
                    </div>
                <input type='hidden' name='id' value='".$_POST['edit']."'>
                <div class='field is-grouped'>
                    <div class='control'>
                        <button type ='submit' class='button is-success' name='edit'>Confirm</button>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-danger' name='cancel'>Cancel</button>
                    </div>
                </div>
                </form>
                </div></div>";
        }
    } else {
        $title = "<h2 class='title is-2 has-text-centered'>Listing of excursions</h2>";
        $content = " 
        <div class='column'>$title
        <div class='table-container'>
                    <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th><a href='?action=list&ord=name'>Name</a></th>
                            <th>Starting point</th>
                            <th>End point</th>
                            <th><a href='?action=list&ord=tarif'>Price</a></th>
                            <th><a href='?action=list&ord=max'>Hikers max</a></th>
                            <th><a href='?action=list&ord=dd'>Start</th>
                            <th><a href='?action=list&ord=da'>End</th>
                            <th>Guides</th>
                            <th>Hikers</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    ";
                    $ord =isset($_GET['ord'])?$_GET['ord']:'';
                    switch ($ord) {
                        case 'name':
                            $ord = 'e_nom';
                            break;
                        case 'tarif':
                            $ord = 'e_tarif';
                            break;
                        case 'max':
                            $ord = 'e_randonneurs_max';
                            break;
                        case 'dd':
                            $ord = 'e_date_depart';
                            break;
                        case 'da':
                            $ord = 'e_date_arrivee';
                            break;
                        default:
                            $ord = 'e_nom';
                            break;
                    }
                    $query = "SELECT DISTINCT e_id, e_nom, e_point_depart, e_point_arrivee, e_tarif, e_randonneurs_max, e_date_depart, e_date_arrivee FROM `nc_excursion` ORDER BY $ord";
                    $reponse = executeSQL($query,array());
                    
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                        <tr>
                            <th class='is-vcentered'>".$donnees['e_nom']." </th>
                            <th class='is-vcentered'>".$donnees['e_point_depart']." </th>
                            <th class='is-vcentered'>".$donnees['e_point_arrivee']." </th>
                            <th class='is-vcentered'>".$donnees['e_tarif']." €</th>
                            <th class='is-vcentered'>".$donnees['e_randonneurs_max']." </th>
                            <th class='is-vcentered'>".$donnees['e_date_depart']." </th>
                            <th class='is-vcentered'>".$donnees['e_date_arrivee']." </th>
                            <th class='is-vcentered'>
                                ";
                        
                                $query = $query = "SELECT DISTINCT  count(ge_g_numero) as guides from nc_guidemeneexcursion WHERE ge_e_id = ?";
                                $guide =executeSQL($query,array($donnees['e_id']));
                                
                                while ($donnees2 = $guide->fetch()) {
                                    $content.= "
                                            <div class='field'>
                                                <label class='label'>".$donnees2['guides']."</label>
                                            </div>";
                                }
                        
                        $content .="
                            </th>
                            <th class='is-vcentered'>";
                            $query = "SELECT count(`b_r_id`) as randonneurs from nc_booking WHERE b_e_id = ?";
                            $rando =executeSQL($query,array($donnees['e_id']));
                            while ($donnees2 = $rando->fetch()) {
                                $content .= "
                                        <div class='field'>
                                            <label class='label'>".$donnees2['randonneurs']."</label>
                                        </div>";
                            }
                            
                        $content.= " </th>
                                    <th>
                                    <div class='field is-grouped'>
                                            <div class='control'>
                                            <form action='' method='post'>
                                                <button type ='submit' class='button is-success' name='edit' value='".$donnees['e_id']."'>Edit</button>
                                            </form>
                                        </div>
                                        <div class='control'>
                                            <button class='button is-danger' name='delete' onclick=\"document.getElementById('id$pos').style.display='block'\" >Delete</button>
                                        </div>
                                    </div>
                                <div id='id$pos' class='modal'>
                                    <form class='modal-content' action='' method='post'>
                                        <div class='container-modal'>
                                            <p class='title is-3'>Delete Excursion ".$donnees['e_nom']."</p>
                                            <p class='title is-4'>Are you sure you want to delete this excursion ?</p>
                                            <div class='buttons is-centered'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-success' name ='cancel'>Cancel</button>
                                                </div>
                                                <div class='control'>
                                                    <button type='submit' class='button is-danger' name='delete' value='".$donnees['e_id']."'>Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                <button class='modal-close is-large' aria-label='close' onclick=\"document.getElementById('id$pos').style.display='none'\"></button>
                                </div> 
                            </th>
                        </tr>
                        ";$pos++;
                    }
                    $content .= "</table></div></div>";
                }
            }
        echo "
            $content
            </div>   
                    </div>       
                </div>       
                </main>
            <script src='./src/excursion.js'></script>
        <script src='./src/script-menu.js'></script>
        </body>
        </html>";