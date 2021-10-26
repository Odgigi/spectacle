<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Développer un site web dynamique en PHP - site Spectacle">
<meta name="keywords" content="PHP spectacle">
<meta name="author" content="Odile Girgiel">

<title>
    ? VOIR ce soir • 
    <?php
        if (isset($_GET['page']) && $_GET['page'] !== null && file_exists('views/' . $_GET['page'] . '.php')) {
            echo $_GET['page'];
        } else {
            echo 'home';
        }
    ?>
</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/fontawesome.css">
<link rel="stylesheet" href="assets/css/style.css">