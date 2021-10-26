<?php
    $id = (int)$_GET['spectacle'];
    $req = $db->query('SELECT * FROM spectacles WHERE id=' . $id);
    $post = $req->fetch();
?>

<div class="container my-3">

    <div class="row">

        <div class="col-12">
            <h1><?= $post['titre'] ?></h1>
            <p>De <?= $post['nom'] ?></p>
            <p>Du <?= $post['premiere'] . ' Au ' . $post['derniere'] ?></p>
        </div>
        
        <div class="col-sm-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3 my-3">
            <img src="assets/img_spectacle/<?= $post['visuel'] ?>" alt="<?= $post['alt'] ?>" class="w-100">
        </div>

        <div class="col-12">
            <p><?= $post['presentation'] ?></p>
        </div>
        
    </div>

</div>