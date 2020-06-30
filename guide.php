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
    
    if(isset($_POST['delete'])) {
        $query = "DELETE FROM `nc_guide` WHERE g_numero = ?";
        executeSQL($query,array($_POST['delete']));     
        $query = "DELETE FROM `nc_guidemeneexcursion` WHERE g_numero = ?";
        executeSQL($query,array($_POST['delete']));
    }

    if($action =='add') {
        $title = "<h2 class='title is-2'>Ajout d'un guide</h2>";
        $content = "   
        <div class='column is-offset-one-quarter'>
        <div class='column is-half '>$title
            <form action='' method='post' id='add_form'>
                <div class='field'>
                    <label for='nom_guide' class='label'>Nom :</label>
                    <div class='control'>
                        <input class='input' type='text' name='nom_guide' id='nom_guide'>         
                    </div>
                    <div id='checkfirstname' class='verif'></div>
                </div>
                <div class='field'>
                    <label for='telephone' class='label'>Telephone :</label>
                    <div class='control'>
                        <input class='input' type='text' name='telephone' id='telephone'>
                    </div>
                    <div id='checkphone' class='verif'></div>
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
                <div id='verif'></div></div></div>";
    } elseif(isset($_POST['edit'])) {

        $query = "SELECT g_numero, g_nom, g_telephone FROM `nc_guide` WHERE g_numero = ?";
        $reponse = executeSQL($query,array($_POST['edit']));
        
        if ($donnees = $reponse->fetch()) {
            $title = "<h2 class='title is-2'>Edition du guide ".$donnees['g_nom'].", n ° : ".$donnees['g_numero']."</h2>";
            $content = "   
            <div class='column is-offset-one-quarter'>
            <div class='column is-half '>$title
                <form action='' method='post' id='edit_form'>
                    <div class='field'>
                        <label for='nom_guide' class='label'>Nom</label>
                        <div class='control'>
                            <input class='input' type='text' name='nom_guide' id='nom_guide' value='".$donnees['g_nom']."'>         
                        </div>
                        <div id='checkfirstname' class='verif'></div>
                    </div>
                    <div class='field'>
                        <label for='telephone' class='label'>telephone</label>
                        <div class='control'>
                            <input class='input' type='text' name='telephone' id='telephone' value='".$donnees['g_telephone']."'>
                        </div>
                        <div id='checkphone' class='verif'></div>
                    </div>
                    <input type='hidden' name='g_num' value='".$donnees['g_numero']."'>
                    <div class='field is-grouped'>
                        <div class='control'>
                            <button type ='submit' class='button is-success'>Modifier</button>
                        </div>
                        <div class='control'>
                        <a href='guide.php' class='button is-danger'>Cancel</a>
                        </div>
                    </div>
                    </form>
                    <div id='verif'></div></div></div>";
                }
    } else {
        $title = "<h2 class='title is-2 has-text-centered'>Affichage des guides</h2>";
        $content = "   
        <div class='column'>$title
        <div class='column'>
        <div class='table-container'>
                <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Telephone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    ";

                    $query = "SELECT g_numero, g_nom, g_telephone FROM `nc_guide` ORDER BY `g_nom`";

                    $reponse = executeSQL($query,array());
                    
                    while ($donnees = $reponse->fetch()) {
                        $id=$donnees['g_numero'];
                        $content .= "
                        <tr>
                            <th class='is-vcentered'>$pos</th>
                            <th class='is-vcentered'>".$id."</th>
                            <th class='is-vcentered'>".$donnees['g_nom']."</th>
                            <th class='is-vcentered'>".$donnees['g_telephone']."</th>
                            <th>
                                <form action='' method='post'>
                                    <div class='field is-grouped'>
                                        <div class='control'>
                                            <button type ='submit' class='button is-success' name='edit' value='$id'>Edit</button>
                                        </div>
                                        <div class='control'>
                                            <button type ='submit' class='button is-danger' onclick=\"return confirm('Are u sure, there is no rolling back !!');\" name='delete' value='$id'>Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </th>
                        </tr>
                        ";$pos++;
                    }
        $content .= "</table></div></div></div>";
    }   
}

echo "

        $content
        </div>   
            </div>       
            </main>
        <script src='./src/guide.js'></script>
    </body>
</html>";