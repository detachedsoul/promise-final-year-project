<?php $pageTitle = 'Admin dashboard | View Pending Complain Details' ?>
<?php $breadcrumb = 'View Pending Complain Details' ?>
<?php require_once("includes/Header.php") ?>

<div class="container-lg">
    <div class="row">
        <?= viewPendingComplainsDetails() ?>
    </div>
</div>

<?php require_once("includes/Footer.php"); ?>