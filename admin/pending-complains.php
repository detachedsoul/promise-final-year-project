<?php $pageTitle = 'Admin dashboard | View Pending Complains' ?>
<?php $breadcrumb = 'View Pending Complains' ?>
<?php require_once("includes/Header.php") ?>

<div class="container-lg">
    <div class="row">
        <div class="col-12 bg-white p-4">
            <h2 class="mb-4">
                Pending Complains
            </h2>

            <?= viewPendingComplains() ?>
        </div>
    </div>
</div>

<?php require_once("includes/Footer.php"); ?>