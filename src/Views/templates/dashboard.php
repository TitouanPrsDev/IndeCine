<!doctype html>
<html lang="fr">

<?php require_once 'src/Views/partials/head.php' ?>

<body>

<?php require_once 'src/Views/partials/header.php' ?>

<?php require_once 'src/Views/partials/nav.php' ?>

<main class="main-dashboard">
    <div class="page-header">
        <?php if (isset($pageData['breadcrumb'])): ?>
        <div class="breadcrumb">
            <a href="/dashboard">Tableau de bord</a>

            <?php foreach(array_slice($pageData['breadcrumb'], 0, count($pageData['breadcrumb']) - 1) as $key => $val): ?>
                <a href="<?= $val ?>"><?= $key ?></a>
            <?php endforeach; ?>

            <p><?= end($pageData['breadcrumb']) ?></p>
        </div>
        <?php endif; ?>

        <h1><?= $pageData['title'] ?></h1>
    </div>

    <div class="page-content">
        <?= $content ?>
    </div>
</main>

</body>
</html>