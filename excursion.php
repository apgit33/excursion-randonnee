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
                <label for='nom_excursion' class='label'>Nom :</label>
                <div class='control'>
                    <input type='text' name='nom_excursion' id='nom_excursion' class='input'>         
                </div>
                <div id='checkfirstname' class='verif'></div>
            </div>
            <div class='field'>
                <label for='point_depart_excursion' class='label'>Point de départ :</label>
                <div class='control'>
                    <div class='select'>
                        <select name='point_depart_excursion' id='point_depart_excursion'>$region</select>
                    </div>
                </div>
            </div>
            <div class='field'>
                <label for='point_arrive_excursion' class='label'>Point d'arrivé :</label>
                <div class='control'>
                    <div class='select'>
                        <select name='point_arrive_excursion' id='point_arrive_excursion'>$region</select>
                    </div>
                </div>
            </div>
            <div class='field'>
                <label for='tarif_excursion' class='label'>Tarif :</label>
                <div class='control'>
                    <input class='input' type='number' name='tarif_excursion' id='tarif_excursion'>
                </div>
                <div id='checktarif' class='verif'></div>
            </div>
            <div class='field'>
                <label for='randonneurs_max_excursion' class='label'>Nombre maximum de randonneurs :</label>
                <div class='control'>
                    <input class='input' type='number' name='randonneurs_max_excursion' id='randonneurs_max_excursion'>     
                </div>
                <div id='checkmax' class='verif'></div>
            </div>
            <div class='field'>
                <label for='date_depart_excursion' class='label'>Date de départ :</label>
                <div class='control'>
                    <input class='input' type='date' name='date_depart_excursion' id='date_depart_excursion'>  
                </div>
                <div id='checkdp' class='verif'></div>
            </div>
            <div class='field'>
                <label for='date_arrivee_excursion' class='label'>Date d'arrivée :</label>
                <div class='control'>
                    <input class='input' type='date' name='date_arrivee_excursion' id='date_arrivee_excursion'>  
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
                    <button type ='submit' class='button is-success' name='add_entry'>Ajouter</button>
                </div>
                <div class='control'>
                    <a href='excursion.php' class='button is-danger'>Cancel</a>
                </div>
            </div>
            </form>
            <div id='verif'></div>";
    } elseif(isset($_POST['edit'])) {

        $query = "SELECT e_nom, e_point_depart, e_point_arrivee, e_tarif, e_randonneurs_max, e_date_depart, e_date_arrivee FROM `nc_excursion` WHERE e_id = ?";
        $reponse = executeSQL($query,array($_POST['edit']));
        if ($donnees = $reponse->fetch()) {
            $title = "<h2 class='title is-2'>Edition de l'excursion n ° ".$_POST['edit']."</h2>";
            $content = "   
            <div class='column is-offset-one-quarter'>
            <div class='column is-half '>$title
            <form action='' method='post' id='edit_form'>
                <div class='field'>
                    <label for='nom_excursion' class='label'>Nom :</label>
                    <div class='control'>
                        <input class='input' type='text' name='nom_excursion' id='nom_excursion' value='".$donnees['e_nom']."'>         
                    </div>
                    <div id='checkfirstname' class='verif'></div>
                </div>
                <div class='field'>
                    <label for='point_depart_excursion' class='label'>Point de départ :</label>
                    <div class='control'>
                        <div class='select'>
                            <select name='point_depart_excursion' id='point_depart_excursion'>    
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
                    <label for='point_arrive_excursion' class='label'>Point d'arrivée :</label>
                    <div class='control'>
                        <div class='select'>
                            <select name='point_arrive_excursion' id='point_arrive_excursion'>";
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
                    <label for='tarif_excursion' class='label'>Tarif :</label>
                    <div class='control'>
                        <input class='input' type='number' name='tarif_excursion' id='tarif_excursion' value='".$donnees['e_tarif']."'>
                    </div>
                    <div id='checktarif' class='verif'></div>
                </div>
                <div class='field'>
                    <label for='randonneurs_max_excursion' class='label'>Nombre maximum de randonneurs :</label>
                    <div class='control'>
                        <input class='input' type='number' name='randonneurs_max_excursion' id='randonneurs_max_excursion' value='".$donnees['e_randonneurs_max']."'>     
                        </div>
                        <div id='checkmax' class='verif'></div>
                    </div>
                <div class='field'>
                    <label for='date_depart_excursion' class='label'>Date de départ :</label>
                    <div class='control'>
                        <input class='input' type='date' name='date_depart_excursion' id='date_depart_excursion' value='".$donnees['e_date_depart']."'>
                        </div>
                        <div id='checkdp' class='verif'></div>
                    </div>
                <div class='field'>
                    <label for='date_arrivee_excursion' class='label'>Date d'arrivée :</label>
                    <div class='control'>
                        <input class='input' type='date' name='date_arrivee_excursion' id='date_arrivee_excursion' value='".$donnees['e_date_arrivee']."'>
                        </div>
                        <div id='checkda' class='verif'></div>
                    </div>
                <input type='hidden' name='id' value='".$_POST['edit']."'>
                <div class='field is-grouped'>
                    <div class='control'>
                        <button type ='submit' class='button is-success'>Modifier</button>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-danger'>Annuler</button>
                    </div>
                </div>
                </form>
                <div id='verif'></div></div></div>";
        }
    } else {
        $title = "<h2 class='title is-2 has-text-centered'>Affichage des excursions</h2>";
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
                            <th class='is-vcentered'>".$donnees['e_tarif']." </th>
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
                                <form action='' method='post'>
                                    <div class='field is-grouped'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-success' name='edit' value='".$donnees['e_id']."'>Edit</button>
                                        </div<
                                        <div class='control'>
                                            <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['e_id']."'>Delete</button>
                                        </div>
                                    </div>
                                </form>
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
            </body>
        </html>";