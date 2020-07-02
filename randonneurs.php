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
        $query = "DELETE FROM `nc_randonneur` WHERE r_id = ?";
        executeSQL($query,array($_POST['delete']));
        $query = "DELETE FROM `nc_booking` WHERE b_r_id = ?";
        executeSQL($query,array($_POST['delete']));
    }

    if($action =='add') {
        $title = "<h2 class='title is-2 has-text-centered'>New Hiker</h2>";
        $content = "   
        <div class='column is-offset-one-quarter'>
        <div class='column is-half '>$title
            <form action='' method='post' id='add_form'>
                <div class='field'>
                    <label for='nom_randonneur' class='label'>First Name :</label>
                    <div class='control'>
                        <input type='text' name='nom_randonneur' id='nom_randonneur' class='input' required>         
                    </div>
                    <div id='checkfirstname' class='verif'></div>
                </div>    
                <div class='field'>
                    <label for='prenom_randonneur' class='label'>Last Name :</label>
                    <div class='control'>
                        <input type='text' name='prenom_randonneur' id='prenom_randonneur' class='input' required>
                    </div>
                    <div id='checklastname' class='verif'></div>
                </div>    
                <div class='field'>
                    <label for='email_randonneur' class='label'>Email :</label>
                    <div class='control'>
                        <input type='email' name='email_randonneur' id='email_randonneur' class='input' required>
                    </div>
                    <div id='checkemail' class='verif'></div>
                </div> 
                <div class='field'>
                <label for='password_randonneur' class='label'>Password :</label>
                    <div class='control'>
                        <input type='password' name='password_randonneur' id='password_randonneur' class='input' required>
                    </div>
                    <div id='checkpass' class='verif'></div>
                </div> 
                <div class='field is-grouped'>
                    <div class='control'>
                        <button type ='submit' class='button is-success' name='add_entry'>Create</button>
                    </div>
                    <div class='control'>
                        <a href='excursion.php' class='button is-danger'>Cancel</a>
                    </div>
                </div>
                </form>
                </div>
                </div>
                ";
    } elseif(isset($_POST['edit'])) {
            $query = "SELECT r_id, r_nom, r_prenom, r_email, r_password FROM `nc_randonneur` WHERE r_id = ?";
            $reponse = executeSQL($query,array($_POST['edit']));
            
            if ($donnees = $reponse->fetch()) {
                $title = "<h2 class='title is-2 has-text-centered'>Edition of ".$donnees['r_nom']." ".$donnees['r_prenom']."</h2>";
                $content = "   
                <div class='column is-offset-one-quarter'>
                <div class='column is-half '>$title
                    <form action='' method='post' id='edit_form'>
                        <div class='field'>
                            <label for='nom_randonneur' class='label'>First Name :</label>
                            <div class='control'>
                                <input class='input' type='text' name='nom_randonneur' id='nom_randonneur' value='".$donnees['r_nom']."' required>         
                            </div>
                            <div id='checkfirstname' class='verif'></div>
                        </div>
                        <div class='field'>
                            <label for='prenom_randonneur' class='label'>Last Name :</label>
                            <div class='control'>
                                <input class='input' type='text' name='prenom_randonneur' id='prenom_randonneur' value='".$donnees['r_prenom']."' required>
                            </div>
                            <div id='checklastname' class='verif'></div>
                        </div>  
                        <div class='field'>
                            <label for='email_randonneur' class='label'>Email :</label>
                            <div class='control'>
                                <input class='input' type='email' name='email_randonneur' id='email_randonneur' value='".$donnees['r_email']."' required>
                            </div>
                            <div id='checkemail' class='verif'></div>
                        </div> 
                        <div class='field'>
                            <label for='password_randonneur' class='label'>Password :</label>
                            <div class='control'>
                                <input class='input' type='password' name='password_randonneur' id='password_randonneur' value='".$donnees['r_password']."' required>
                            </div>
                            <div id='checkpass' class='verif'></div>
                        </div> 
                        <div class='field is-grouped'>
                            <div class='control'>
                            <input type ='hidden' name='id' value='".$donnees['r_id']."'>
                            </div>
                            <div class='control'>
                                <button type ='submit' class='button is-success' name='edit'>Confirm</button>
                            </div>
                            <div class='control'>
                                <a href='randonneurs.php' class='button is-danger' name='cancel'>Cancel</a>
                            </div>
                        </div>
                    </form>
                    </div></div>";
            }
        } else {
            $title = "<h2 class='title is-2 has-text-centered'>Listing of Hikers</h2>";
            $content = " 
            <div class='column'>$title
            <div class='table-container'>
                <table class='table is-mobile is-striped'>
                    <thead>
                        <tr>
                            <th>Pos</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    ";

                    $query = "SELECT r_id, r_nom, r_prenom, r_email, r_password FROM `nc_randonneur` ORDER BY `r_nom`";

                    $reponse = executeSQL($query,array());
                    while ($donnees = $reponse->fetch()) {
                        $content .= "
                        <tr id='rando'>
                            <th class='is-vcentered'>$pos</th>
                            <th class='is-vcentered'>".$donnees['r_nom']." </th>
                            <th class='is-vcentered'>".$donnees['r_prenom']." </th>
                            <th class='is-vcentered'>".$donnees['r_email']." </th>
                            <th class='is-vcentered'>".$donnees['r_password']." </th>
                            <th class='is-vcentered'>
                                    <div class='field is-grouped'>
                                            <div class='control'>
                                                <form action='' method='post'>
                                                    <button type ='submit' class='button is-success' name='edit' value='".$donnees['r_id']."'>Edit</button>
                                                </form>
                                            </div>
                                       
                                        <div class='control'>
                                            <button class='button is-danger' name='delete' onclick=\"document.getElementById('id$pos').style.display='block'\" >Delete</button>
                                        </div>
                                    </div>
                                <div id='id$pos' class='modal'>
                                    <form class='modal-content' action='' method='post'>
                                        <div class='container-modal'>
                                            <p class='title is-3'>Delete ".$donnees['r_nom']."&nbsp;".$donnees['r_prenom']."</p>
                                            <p class='title is-4'>Are you sure you want to delete this hiker ?</p>
                                            <div class='buttons is-centered'>
                                                <div class='control'>
                                                    <button type='submit' class='button is-success' name ='cancel'>Cancel</button>
                                                </div>
                                                <div class='control'>
                                                    <button type='submit' class='button is-danger' name='delete' value='".$donnees['r_id']."'>Delete</button>
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
            </main>
        <script src='./src/randonneur.js'></script>
        <script src='./src/script-menu.js'></script>
    </body>
</html>";