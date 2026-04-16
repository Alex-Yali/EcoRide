<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php require APP_ROOT . "/templates/pages/includes/header.php" ?>

    <h1><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></h1>

    <?php require APP_ROOT . "/templates/pages/includes/footer.php" ?>
</body>

</html>