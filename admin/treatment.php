<?php

session_start();

require_once('../config/database.php');

if ($_SERVER['HTTP_REFERER'] == 'http://localhost/PHP/spectacle/admin/form.php') { // vérifie qu'on vient bien du formulaire

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
    if (empty($_FILES['visuel']['name']) || $_FILES['visuel']['size'] > 2000000 || !in_array($_FILES['visuel']['type'], $authorizedFormats)) {
        $errorMessage .= '<p>- l\'image est obligatoire, ne doit pas dépasser 2 Mo et doit être au format PNG, JPG, JPEG, JP2 ou WEBP.</p>';
        $validation = false;
    }
    
    if ($validation === true) {
        $timestamp = time(); // récupère le nombre de secondes écoulées depuis le 1er janvier 1970
        $format = strchr($_FILES['visuel']['name'], '.'); // récupère tout ce qui se trouve après le point (png, jpg, ...)
        $imgName = $timestamp . $format; // crée le nouveau nom d'image
        $req = $db->prepare('INSERT INTO spectacles (titre, nom, categorie, premiere, derniere, ville, salle, visuel, alt, presentation) VALUES (:titre, :nom, :categorie, :premiere, :derniere, :ville, :salle, :visuel, :alt,:presentation)');
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
        move_uploaded_file($_FILES['visuel']['tmp_name'], '../assets/img_spectacle/' . $imgName); // upload du fichier
        $_SESSION['notification'] = 'Le spectacle a bien été ajouté';
        header('Location: index.php'); // redirection
    } else {
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
        header('Location: form.php'); // redirection
    }

    /*
    if (!empty($title) && strlen($title) <= 100) { // vérifie le titre
        if (!empty($content) && strlen($content) <= 65535) { // vérifie le contenu
            if (!empty($alt) && strlen($alt) <= 100) { // vérifie le champ alt
                if (!empty($author) && strlen($author) <= 45) { // vérifie le champ alt
                    if (!empty($published) && ($published === 'true' || $published === 'false')) { // vérifie le champ published
                        if (!empty($_FILES['img']['name']) && $_FILES['img']['size'] <= 2000000) { // vérifie la présence et la taille de l'image
                            if ($_FILES['img']['type'] == 'image/png' || $_FILES['img']['type'] == 'image/jpg' || $_FILES['img']['type'] == 'image/jpeg' || $_FILES['img']['type'] == 'image/jp2' || $_FILES['img']['type'] == 'image/webp') { // vérifie le type de fichier

                                $timestamp = time(); // récupère le nombre de secondes écoulées depuis le 1er janvier 1970
                                $format = strchr($_FILES['img']['name'], '.'); // récupère tout ce qui se trouve après le point (png, jpg, ...)
                                $imgName = $timestamp . $format; // crée le nouveau nom d'image

                                $req = $db->prepare('INSERT INTO post (title, content, img, alt, author, created_at, published) VALUES (:title, :content, :img, :alt, :author, NOW(), :published)'); // prépare la requête
                                $req->bindParam(':title', $title, PDO::PARAM_STR); // associe la valeur $title à :title
                                $req->bindParam(':content', $content, PDO::PARAM_STR); // associe la valeur $content à :content
                                $req->bindParam(':img', $imgName, PDO::PARAM_STR); // associe la valeur $imgName à :img
                                $req->bindParam(':alt', $alt, PDO::PARAM_STR); // associe la valeur $alt à :alt
                                $req->bindParam(':author', $author, PDO::PARAM_STR); // associe la valeur $author à :author
                                $req->bindParam(':published', $published, PDO::PARAM_BOOL); // associe la valeur $published à :published
                                $req->execute(); // exécute la requête

                                move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/posts/' . $imgName); // upload du fichier

                            } else {
                                echo 'l\'image doit être au format png, jpg, jpeg, jp2 ou webp';
                            }
                        } else {
                            echo 'le champ "image" est obligatoire et l\'image doit peser moins de 2 Mo';
                        }
                    } else {
                        echo 'le champ "publier" est obligatoire et doit être soit "oui", soit "non";
                    }
                } else {
                    echo 'le champ "auteur" est obligatoire et doit comporter moins de 45 caractères';
                }
            } else {
                echo 'le champ "alt" est obligatoire et doit comporter moins de 100 caractères';
            }
        } else {
            echo 'le champ "contenu" est obligatoire et doit comporter moins de 65535 caractères';
        }
    } else {
        echo 'le champ "titre" est obligatoire et doit comporter moins de 100 caractères';
    }*/

} elseif (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $req = $db->query('SELECT visuel FROM spectacles WHERE id=' . $id); // récupère le nom de l'image
    $oldImg = $req->fetch();
    if (file_exists('../assets/img_spectacle/' . $oldImg['visuel'])) { // vérifie que le fichier existe
        unlink('../assets/img_spectacle/' . $oldImg['visuel']); // supprime l'image du dossier local
    }
    $reqDelete = $db->query('DELETE FROM spectacles WHERE id=' . $id); // supprime les données en bdd
    $_SESSION['notification'] = 'Le spectacle a bien été supprimé';
    header('Location: index.php'); // redirection
}

?>