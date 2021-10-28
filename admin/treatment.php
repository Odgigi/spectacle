<?php

session_start();

require_once('../config/database.php');

if ($_SERVER['HTTP_REFERER'] == 'http://localhost/PHP/spectacle/admin/form.php' || (isset($_GET['update']) && !empty($_GET['update']))) { // vérifie qu'on vient bien du formulaire

    // nettoyage des données
    $title = htmlspecialchars($_POST['titre']);
    $author = htmlspecialchars($_POST['nom']);
    $category = htmlspecialchars($_POST['categorie']);
    $premiere = date_format (new \DateTime($_POST['premiere']), "Y-m-d H:i");
    $derniere = date_format (new \DateTime($_POST['derniere']), "Y-m-d H:i");
    $town = htmlspecialchars($_POST['ville']);
    $theater = htmlspecialchars($_POST['salle']);
    $alt = htmlspecialchars($_POST['alt']);
    $content = htmlspecialchars($_POST['presentation']);

    $errorMessage = '<p>Merci de vérifier les points suivants :</p>';
    $validation = true;

    if ($_GET['update']) {
        $action = 'update';
        $id = (int)$_GET['update'];
    } else {
        $action = 'create';
    }

    // vérification du titre
    if (empty($title) || strlen($title) > 200) {
        $errorMessage .= '<p>- le champ "titre" est obligatoire et doit comporter moins de 200 caractères.</p>';
        $validation = false;
    }

    // vérification du champ nom (de la Cie ou de l'artiste)
    if (empty($author) || strlen($author) > 150) {
        $errorMessage .= '<p>- le champ "nom" est obligatoire et doit comporter moins de 150 caractères.</p>';
        $validation = false;
    }

    // vérification du champ categorie
    if (empty($category) || strlen($category) > 30) {
        $errorMessage .= '<p>- le champ "categorie" est obligatoire et doit comporter moins de 30 caractères.</p>';
        $validation = false;
    }

    // vérification du champ ville
    if (empty($town) || strlen($town) > 100) {
        $errorMessage .= '<p>- le champ "ville" est obligatoire et doit comporter moins de 100 caractères.</p>';
        $validation = false;
    }

    // vérification du champ salle
    if (empty($theater) || strlen($theater) > 100) {
        $errorMessage .= '<p>- le champ "salle" est obligatoire et doit comporter moins de 100 caractères.</p>';
        $validation = false;
    }

    // vérification du champ alt
    if (empty($alt) || strlen($alt) > 100) {
        $errorMessage .= '<p>- le champ "alt" est obligatoire et doit comporter moins de 100 caractères.</p>';
        $validation = false;
    }

    // vérification du champ Presentation
    if (empty($content) || strlen($content) > 65535) {
        $errorMessage .= '<p>- le champ "presentation" est obligatoire et doit comporter moins de 65535 caractères.</p>';
        $validation = false;
    }

    // vérification de l'image
    $authorizedFormats = [
        'image/png',
        'image/jpg',
        'image/jpeg',
        'image/jp2',
        'image/webp'
    ];

    // Vérification de l'image
    if ($action === 'create' || ($action === 'update' && $_FILES['visuel']['name'])) {  // (seulement) si création ou si besoin d'une image lors de la modification
        if (empty($_FILES['visuel']['name']) || $_FILES['visuel']['size'] > 2000000 || !in_array($_FILES['visuel']['type'], $authorizedFormats)) {
            $errorMessage .= '<p>- l\'image est obligatoire, ne doit pas dépasser 2 Mo et doit être au format PNG, JPG, JPEG, JP2 ou WEBP.</p>';
            $validation = false;
        } else {
            $timestamp = time(); // récupère le nombre de secondes écoulées depuis le 1er janvier 1970
            $format = strchr($_FILES['visuel']['name'], '.'); // récupère tout ce qui se trouve après le point (png, jpg, ...)
            $imgName = $timestamp . $format; // crée le nouveau nom d'image
            move_uploaded_file($_FILES['visuel']['tmp_name'], '../assets/img_spectacle/' . $imgName); // upload du fichier
        }
    }

    if ($validation === true) {
        if ($action === 'create') {  // pour la création
            $req = $db->prepare('INSERT INTO spectacles (titre, nom, categorie, premiere, derniere, ville, salle, visuel, alt, presentation) VALUES (:titre, :nom, :categorie, :premiere, :derniere, :ville, :salle, :visuel, :alt,:presentation)');  // on prépare la requête
        } else {
            $reqImg = $db->query('SELECT visuel FROM spectacles WHERE id=' . $id);
            $oldImg = $reqImg->fetch();
            if (isset($imgName)) {
                if (file_exists('../assets/img_spectacle' . $oldImg['visuel'])) {
                    unlink('../assets/img_spectacle' . $oldImg['visuel']);  
                }
            } else {  // pour l'update
                $imgName = $oldImg['visuel'];
            }
            $req = $db->prepare('UPDATE spectacles SET titre=:titre, nom=:nom, categorie=:categorie, premiere=:premiere, derniere=:derniere, ville=:ville, salle=:salle, visuel=:visuel, alt=:alt, presentation=:presentation WHERE id=' .$id);  // idem pour l'update
        }
        $req->bindParam(':titre', $title, PDO::PARAM_STR);
        $req->bindParam(':nom', $author, PDO::PARAM_STR);
        $req->bindParam(':categorie', $category, PDO::PARAM_STR);
        $req->bindParam(':premiere', $premiere, PDO::PARAM_STR);
        $req->bindParam(':derniere', $derniere, PDO::PARAM_STR);
        $req->bindParam(':ville', $town, PDO::PARAM_STR);
        $req->bindParam(':salle', $theater, PDO::PARAM_STR);
        $req->bindParam(':visuel', $imgName, PDO::PARAM_STR);
        $req->bindParam(':alt', $alt, PDO::PARAM_STR);
        $req->bindParam(':presentation', $content, PDO::PARAM_STR);
        $req->execute(); // exécute la requête
        if ($action === 'create') {
            $_SESSION['notification'] = 'Le spectacle a bien été ajouté';
        } else {
            $_SESSION['notification'] = 'Le spectacle a bien été modifié';
        }
        header('Location: index.php'); // redirection vers l'espace administrateur où sont stockées les données
    } else {  // s'il y a une erreur, sinon s'arrête au-dessus
        $_SESSION['notification'] = $errorMessage;
        $_SESSION['form'] = [
            'title' => $title,
            'nom' => $author,
            'categorie' => $category,
            'premiere' => $premiere,
            'derniere' => $derniere,
            'ville' => $town,
            'salle' => $theater,
            'alt' => $alt,
            'presentation' => $content,
        ];
        header('Location: form.php'); // redirection vers le formulaire qui affiche le message d'erreur
    }
} elseif (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $req = $db->query('SELECT visuel FROM spectacles WHERE id=' . $id); // récupère le nom de l'image
    $oldImg = $req->fetch();
    if (file_exists('../assets/img_spectacle/' . $oldImg['visuel'])) { // vérifie que le fichier existe
        unlink('../assets/img_spectacle/' . $oldImg['visuel']); // supprime l'image du dossier local
    }
    $reqDelete = $db->query('DELETE FROM spectacles WHERE id=' . $id); // supprime les données en bdd
    $_SESSION['notification'] = 'Le spectacle a bien été supprimé';
    header('Location: index.php'); // redirection vers l'espace administrateur qui affiche la suppression de l'id
}

?>