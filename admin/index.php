<?php
    session_start();
    if (isset($_SESSION['notification'])) {
        echo $_SESSION['notification'];
    }
    session_destroy();
    require_once('../config/database.php');
    $req = $db->query('SELECT id, titre, nom, categorie, premiere, derniere, ville, salle, visuel, alt, presentation FROM spectacles ORDER BY id DESC');
    $posts = $req->fetchAll();
?>

<!DOCTYPE html>

<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Spectacle • espace administrateur</title>

        <link rel="stylesheet" href="../assets/css/fontawesome.css">

    </head>

    <body>

        <h1>Espace administrateur</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TITRE</th>
                    <th>NOM CIE/ARTISTE</th>
                    <th>CATEGORIE</th>
                    <th>PREMIERE</th>
                    <th>DERNIERE</th>
                    <th>VILLE</th>
                    <th>SALLE</th>
                    <th>VISUEL</th>
                    <TH>PRESENTATION</TH>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($posts as $post) { ?>
                        <tr>
                            <td><?= $post['id'] ?></td>
                            <td><?= $post['titre'] ?></td>
                            <td><?= $post['nom'] ?></td>
                            <td><?= $post['categorie'] ?></td>
                            <td><?= $post['premiere'] ?></td>
                            <td><?= $post['derniere'] ?></td>
                            <td><?= $post['ville'] ?></td>
                            <td><?= $post['salle'] ?></td>
                            <td><img src="../assets/img_spectacle/<?= $post['visuel'] ?>" alt="<?= $post['alt'] ?>"></td>
                            <td><?= $post['presentation'] ?></td>
                            <td>
                                <a href="#"><i class="fas fa-pen-square"></i></a>
                                <a href="treatment.php?delete=<?= $post['id'] ?>"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>    
                    <?php }
                ?>
            </tbody>
        </table>

        <a href="form.php">Ajouter un spectacle</a>
        
    </body>
</html>
<?php
        //  if(isset($_POST['update'])) {
        //     $dbhost = 'localhost';
        //     $dbname = 'spectacle';
        //     $dbuser = 'root';
        //     $dbpass = '';
            
        //     $conn = mysql_connect($dbhost, $dbname, $dbuser, $dbpass);
            
        //     if(! $conn ) {
        //        die('Could not connect: ' . mysql_error());
        //     }
            
        //     $id = (int)$_POST['update'];
        //     $reqUpdate = $db->query('UPDATE spectacles WHERE id=' . $id);
        //     $sql = "UPDATE spectacles (titre, nom, categorie, premiere, derniere, ville, salle, visuel, alt, presentation)". "SET (:titre, :nom, :categorie, :premiere, :derniere, :ville, :salle, :visuel, :alt,:presentation) ". 
        //        "WHERE id= $id";
        //     mysql_select_db('test_db');
        //     $retval = mysql_query( $sql, $conn );
            
        //     if(! $retval ) {
        //        die('Could not update data: ' . mysql_error());
        //     }
        //     echo "Updated data successfully\n";
            
        //     mysql_close($conn);
        //  }
?>