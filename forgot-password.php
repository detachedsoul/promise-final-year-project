<?php
$pageTitle = "Rivers State University Complaint Portal | Forgot Password";
require_once("includes/functions.php");
require_once("includes/Header.php");
?>
<main class="form-container container pt-4 pb-4">
    <form method="POST">
        <img class="mb-4 text-center d-block mx-auto" src="assets/img/favicon.ico" alt="" width="72" height="57">

        <?= resetPassword() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
        </div>

        <button type="submit" class="btn btn-primary d-block mx-auto" name="reset-password">Reset Password</button>

        <a class="text-primary mt-4 ms-100 d-block" href="login.php">
            Login instead
        </a>
    </form>

</main>
<?php
require_once("includes/Footer.php");
