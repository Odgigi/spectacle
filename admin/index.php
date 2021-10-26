<?php
    session_start();
    if (isset($_SESSION['notification'])) {
        echo $_SESSION['notification'];
    }
    session_destroy();
    require_once('../config/database.php');
    $req = $db->query('SELECT id, titre, nom, catégorie, première, dernière, ville, salle, visuel, alt, présentation FROM spectacles ORDER BY id DESC');
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
                    <th>TEXTE ALTERNATIF</th>
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
                            <td><?= $post['catégorie'] ?></td>
                            <td><?= $post['première'] ?></td>
                            <td><?= $post['dernière'] ?></td>
                            <td><?= $post['ville'] ?></td>
                            <td><?= $post['salle'] ?></td>
                            <td><?= $post['visuel'] ?></td>
                            <td><?= $post['alt'] ?></td>
                            <td><?= $post['présentation'] ?></td>
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