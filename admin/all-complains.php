<?php $pageTitle = 'Admin dashboard | View All Complains' ?>
<?php $breadcrumb = 'View All Complains' ?>
<?php require_once("includes/Header.php") ?>

<div class="container-lg">
    <div class="row">
        <div class="col-12 bg-white p-4">
            <h2 class="mb-4">
                All Complains
            </h2>

            <?= viewAllComplains() ?>
        </div>
    </div>
</div>

<?php require_once("includes/Footer.php"); ?>