<?php $pageTitle = 'Admin dashboard | Complain Details' ?>
<?php $breadcrumb = 'Complain Details' ?>
<?php require_once("includes/Header.php") ?>

<div class="container-lg">
    <div class="row">
        <?= viewResolvedComplainsDetails() ?>
    </div>
</div>

<?php require_once("includes/Footer.php"); ?>