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
        doIt2($query,$_POST['delete'],'');     
        $query = "DELETE FROM `nc_guidemeneexcursion` WHERE ge_e_id = ?";
        doIt2($query,$_POST['delete'],''); 
        $query = "DELETE FROM `nc_booking` WHERE b_e_id = ?";
        doIt2($query,$_POST['delete'],''); 
    }
    if(isset($_POST['modify'])) {
        $query = "UPDATE `nc_excursion` SET e_nom = ?, e_point_depart = ?, e_point_arrivee = ?,e_date_depart = ?,e_date_arrivee = ?,e_tarif = ?,e_randonneurs_max = ?  WHERE `e_id` = ?";
        $co = connect();		
        $sth = $co->prepare($query);
        $sth->bindParam(1,$_POST['nom_excursion']);
        $sth->bindParam(2,$_POST['point_depart_excursion']);
        $sth->bindParam(3,$_POST['point_arrive_excursion']);
        $sth->bindParam(4,$_POST['date_depart_excursion']);
        $sth->bindParam(5,$_POST['date_arrive_excursion']);
        $sth->bindParam(6,$_POST['tarif_excursion']);
        $sth->bindParam(7,$_POST['randonneurs_max_excursion']);
        $sth->bindParam(8,$_POST['modify']);
        $sth->execute();

    }
    if(isset($_POST['add_entry'])) {

        $query = "INSERT INTO nc_excursion (e_nom, e_point_depart, e_point_arrivee, e_date_depart, e_date_arrivee, e_tarif, e_randonneurs_max) VALUES(?,?,?,?,?,?,?)"; 
        
        $co = connect();		
        $sth = $co->prepare($query);
        $sth->bindParam(1,$_POST['nom_excursion']);
        $sth->bindParam(2,$_POST['point_depart_excursion']);
        $sth->bindParam(3,$_POST['point_arrive_excursion']);
        $sth->bindParam(4,$_POST['date_depart_excursion']);
        $sth->bindParam(5,$_POST['date_arrive_excursion']);
        $sth->bindParam(6,$_POST['tarif_excursion']);
        $sth->bindParam(7,$_POST['randonneurs_max_excursion']);
        $sth->execute();
        $id = $co->lastinsertid();
        if(isset($_POST['guide_ids'])) {
            foreach ($_POST['guide_ids'] as $guide_id){
                $query = "INSERT INTO nc_guidemeneexcursion VALUES (?,?)";
                $sth = $co->prepare($query);
                $sth->bindParam(1,$id);
                $sth->bindParam(2,$guide_id);
                $sth->execute();
            }
        }
    }

    if($action =='add') {
        $reponse = doIt2("SELECT DISTINCT `d_nom` FROM `nc_district` ORDER BY `d_nom`",'','');
        while ($donnees = $reponse->fetch()){
            $region .= "<option value ='".$donnees['d_nom']."'>".$donnees['d_nom']."</option>";
        }
        $title = "<h2 class='title is-2'>Ajout d'une excursion</h2>";
        $content = "
        <form action='' method='post' id='add_form'>
            <div class='field'>
                <label for='nom_excursion' class='label'>Nom :</label>
                <div class='control'>
                    <input type='text' name='nom_excursion' id='nom_excursion' class='input'>         
                </div>
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
            </div>
            <div class='field'>
                <label for='randonneurs_max_excursion' class='label'>Nombre maximum de randonneurs :</label>
                <div class='control'>
                    <input class='input' type='number' name='randonneurs_max_excursion' id='randonneurs_max_excursion'>     
                </div>
            </div>
            <div class='field'>
                <label for='date_depart_excursion' class='label'>Date de départ :</label>
                <div class='control'>
                    <input class='input' type='date' name='date_depart_excursion' id='date_depart_excursion'>  
                </div>
            </div>
            <div class='field'>
                <label for='date_arrive_excursion' class='label'>Date de départ :</label>
                <div class='control'>
                    <input type='date' name='date_arrive_excursion' id='date_arrive_excursion'>  
                </div>
            </div>
            <div class='field'>
                <fieldset>
                    <legend class='label'>Guides :</legend>";

                $query = $query = "SELECT DISTINCT  g_numero, g_nom from nc_guide ";
                $reponse2 =doIt2($query,'','');
                $guides = $reponse2->fetchAll();

                foreach($guides as $guide){
                    $content.= "<label class='checkbox'>
                    <input type='checkbox' name='guide_ids[]' value='".$guide['g_nom']."'>
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
                    <button type ='submit' class='button is-danger'>Reset</button>
                </div>
            </div>
        </form>";
    } elseif(isset($_POST['edit'])) {

        $query = "SELECT e_nom, e_point_depart, e_point_arrivee, e_tarif, e_randonneurs_max, e_date_depart, e_date_arrivee FROM `nc_excursion` WHERE e_id = ?";
        $reponse = doIt2($query,$_POST['edit'],'');
        if ($donnees = $reponse->fetch()) {
            $title = "<h2 class='title is-2'>Edition de l'excursion n ° ".$_POST['edit']."</h2>";
            $content = "
            <form action='' method='post' id='add_form'>
                <div class='field'>
                    <label for='nom_excursion' class='label'>Nom :</label>
                    <div class='control'>
                        <input class='input' type='text' name='nom_excursion' id='nom_excursion' value='".$donnees['e_nom']."'>         
                    </div>
                </div>
                <div class='field'>
                    <label for='point_depart_excursion' class='label'>Point de départ :</label>
                    <div class='control'>
                        <div class='select'>
                            <select name='point_depart_excursion' id='point_depart_excursion'>    
                    ";
                $reponse = doIt2("SELECT DISTINCT `d_nom` FROM `nc_district` ORDER BY `d_nom`",'','');
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
                $reponse = doIt2("SELECT DISTINCT `d_nom` FROM `nc_district` ORDER BY `d_nom`",'','');
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
                </div>
                <div class='field'>
                    <label for='randonneurs_max_excursion' class='label'>Nombre maximum de randonneurs :</label>
                    <div class='control'>
                        <input class='input' type='number' name='randonneurs_max_excursion' id='randonneurs_max_excursion' value='".$donnees['e_randonneurs_max']."'>     
                    </div>
                </div>
                <div class='field'>
                    <label for='date_depart_excursion' class='label'>Date de départ :</label>
                    <div class='control'>
                        <input class='input' type='date' name='date_depart_excursion' id='date_depart_excursion' value='".$donnees['e_date_depart']."'>
                    </div>
                </div>
                <div class='field'>
                    <label for='date_arrive_excursion' class='label'>Date de départ :</label>
                    <div class='control'>
                        <input class='input' type='date' name='date_arrive_excursion' id='date_arrive_excursion' value='".$donnees['e_date_arrivee']."'>
                    </div>
                </div>
                <div class='field is-grouped'>
                    <div class='control'>
                        <button type ='submit' class='button is-success' name='modify' value='".$_POST['edit']."'>Modifier</button>
                    </div>
                    <div class='control'>
                        <button type ='submit' class='button is-danger'>Annuler</button>
                    </div>
                </div>
            </form>";
        }
        
    } else {
        $title = "<h2 class='title is-2'>Affichage des excursions</h2>";
        $content = "
                    <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th><a href='?action=list&ord=name'>Nom</a></th>
                            <th>Point de départ</th>
                            <th>Point d'arrivée</th>
                            <th><a href='?action=list&ord=tarif'>Tarif</a></th>
                            <th><a href='?action=list&ord=max'>Max randonneurs</a></th>
                            <th><a href='?action=list&ord=dd'>Date de départ</th>
                            <th><a href='?action=list&ord=da'>Date d'arrivée</th>
                            <th>Guides inscrits</th>
                            <th>Randonneurs inscrits</th>
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
                    $reponse = doIt2($query,'','');
                    
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
                                $guide =doIt2($query,$donnees['e_id'],'');
                                
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
                            $rando =doIt2($query,$donnees['e_id'],'');
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
                                            <button type ='submit' class='button is-success' name='edit' value='".$donnees['e_id']."'>Editer</button>
                                        </div<
                                        <div class='control'>
                                            <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='".$donnees['e_id']."'>Supprimer</button>
                                        </div>
                                    </div>
                                </form>
                            </th>
                        </tr>
                        ";
                    }
                    $content .= "</table>";
                }
            }
echo $content;
include 'footer.php';