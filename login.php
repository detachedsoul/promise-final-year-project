<?php
$pageTitle = "Rivers State University Complaint Portal | Login";
require_once("includes/functions.php");
require_once("includes/Header.php");
?>
<main class="form-container">
    <form class="form-signin w-100 m-auto" method="POST">
        <img class="mb-4 text-center d-block mx-auto" src="assets/img/favicon.ico" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal text-center">
            Login
        </h1>

        <div class="mb-3 w-100">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email address">
        </div>
        <div class="mb-3 w-100">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter your password">
        </div>

        <button type="submit" class="btn btn-primary d-block mx-auto">Login</button>

        <a class="text-danger mt-4 ms-100 d-block" href="forgot-password.php">
            Forgot password
        </a>
    </form>

</main>
<?php
require_once("includes/Footer.php");
