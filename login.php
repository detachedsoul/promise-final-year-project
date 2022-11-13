<?php
$pageTitle = "Rivers State University Complaint Portal | Login";
require_once("includes/functions.php");
require_once("includes/Header.php");
?>
<main class="form-container container pt-4 pb-4">
    <form method="POST">
        <img class="mb-4 text-center d-block mx-auto" src="assets/img/favicon.ico" alt="" width="72" height="57">
        <?= login() ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
        </div>

        <button type="submit" class="btn btn-primary d-block mx-auto" name="login">Login</button>

        <a class="text-danger mt-4 ms-100 d-block" href="forgot-password.php">
            Forgot password
        </a>
    </form>

</main>
<?php
require_once("includes/Footer.php");
