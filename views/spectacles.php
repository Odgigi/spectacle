<div class="container my-3">

    <h1>Spectacles</h1>

    <div class="row">
        <div class="col-12">
            <?php
                $req = $db->query('SELECT * FROM spectacles ORDER BY id DESC');
                $posts = $req->fetchAll();
            ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Titre</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Première</th>
                        <th>Dernière</th>
                        <th>Où</th>
                        <th>Visuel</th>
                        <th>Extrait</th>
                        <th>Lien</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i = 1;
                        foreach ($posts as $post) { ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $post['titre'] ?></td>
                                <td><?= $post['nom'] ?></td>
                                <td><?= $post['catégorie'] ?></td>
                                <td><?= date('Y-m-d', strtotime($post['première'])) ?></td>
                                <td><?= date('Y-m-d', strtotime($post['dernière'])) ?></td>
                                <td><?= $post['ville'] . '-' . ['salle'] ?></td>
                                <td><img src="assets/img-spectacles/<?= $post['visuel'] ?>" alt="<?= $post['alt'] ?>"></td>
                                <td><?= substr($post['présentation'], 0, 200) ?></td>
                                <td><a href="index.php?page=spectacles&spectacle=<?= $post['id'] ?>"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        <?php 
                            $i++;   
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>