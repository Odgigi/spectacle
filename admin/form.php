<?php
    session_start();
    if (isset($_SESSION['notification'])) {
        echo $_SESSION['notification'];
    }
    session_destroy();
?>

<!DOCTYPE html>

<html lang="fr">

    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>SPECTACLE • admin - form</title>

    </head>

    <body>
        
        <form action="treatment.php" method="post" enctype="multipart/form-data">

            <label for="titre">Titre</label>
            <input type="text" name="titre" maxlength="200" value="<?= isset($_SESSION['form']['titre']) ? $_SESSION['form']['titre'] : null ?>" required>

            <label for="nom">Nom de la compagnie/ Artiste</label>
            <input type="text" name="nom" maxlength="150" value="<?= isset($_SESSION['form']['nom']) ? $_SESSION['form']['nom'] : null ?>" required>

            <!-- <label for="catégorie">Catégorie</label> -->
            <!-- <input type="text" name="catégorie" maxlength="30" value="<?= isset($_SESSION['form']['catégorie']) ? $_SESSION['form']['catégorie'] : null ?>" required> -->
            <select name="catégorie" required>
                <option value="">-- choisir --</option>
                <option value="arts-vivants">Arts vivants</option>
                <option value="danse">Danse</option>
                <option value="musique">Musique</option>
                <option value="théâtre">Théâtre</option>
            </select>
            
            <label for="première">Première</label>
            <input type="datetime-local" id="firstshowtime" name="première" value="<?= isset($_SESSION['form']['première']) ? $_SESSION['form']['première'] : null ?>" required>

            <label for="dernière">Dernière</label>
            <input type="datetime-local" id="lastshowtime" name="dernière" value="<?= isset($_SESSION['form']['dernière']) ? $_SESSION['form']['dernière'] : null ?>" required>

            <label for="ville">Ville</label>
            <input type="text" name="ville" maxlength="100" value="<?= isset($_SESSION['form']['ville']) ? $_SESSION['form']['ville'] : null ?>" required>

            <label for="salle">Salle</label>
            <input type="text" name="salle" maxlength="100" value="<?= isset($_SESSION['form']['salle']) ? $_SESSION['form']['salle'] : null ?>" required>

            <label for="visuel">Visuel du spectacle</label>
            <input type="file" name="visuel" accept="image/png, image/jpg, image/jpeg, image/jp2, image/webp" required>

            <label for="alt">Texte alternatif</label>
            <input type="text" name="alt" maxlenght="100" value="<?= isset($_SESSION['form']['alt']) ? $_SESSION['form']['alt'] : null ?>" required>

            <label for="présentation">Présentation</label>
            <textarea name="présentation" cols="30" rows="10" maxlength="65535" required><?= isset($_SESSION['form']['présentation']) ? $_SESSION['form']['présentation'] : null ?></textarea>
            

            <input type="submit" value="Créer">

        </form>

    </body>

</html>