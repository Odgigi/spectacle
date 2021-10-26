<?php
    require_once('config/database.php');
?>

<!DOCTYPE html>

<html lang="fr">

    <head>
        <?php require_once('views/head.php') ?>
    </head>

    <body>

        <header>
            <?php require_once('views/header.php') ?>
        </header>

        <main>
            <?php
                if (isset($_GET['page']) && $_GET['page'] !== null && file_exists('views/' . $_GET['page'] . '.php')) {
                    include_once('views/' . $_GET['page'] . '.php');
                } else {
                    include_once('views/home.php');
                }
            ?>
        </main>

        <footer>
            <?php include_once('views/footer.php') ?>
        </footer>

        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/script.js"></script>
        
    </body>

</html>