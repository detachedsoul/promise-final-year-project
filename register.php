<?php
$pageTitle = "Rivers State University Complaint Portal | Register";
require_once("includes/functions.php");
require_once("includes/Header.php");
?>
<main class="form-container container pt-4 pb-4">
    <form method="POST">
        <img class="mb-4 text-center d-block mx-auto" src="assets/img/favicon.ico" alt="" width="72" height="57">

        <?= register() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="full-name" class="form-label">Full name</label>
                <input type="text" class="form-control" id="full-name" name="full-name" placeholder="Enter your full name">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
            </div>
            <div class="col-md-12 mb-3">
                <label for="complaint" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="register">Register</button>
    </form>
</main>
<?php
require_once("includes/Footer.php");
