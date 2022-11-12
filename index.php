<?php
require_once("includes/functions.php");
require_once("includes/Header.php");
?>
<main class="form-container">
    <form class="form-signin w-100 m-auto" method="POST">
        <img class="mb-4 text-center d-block mx-auto" src="assets/img/favicon.ico" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal text-center">
            Fill in this form to file your complaint
        </h1>

        <div class="mb-3 w-100">
            <label for="full-name" class="form-label">Full name</label>
            <input type="text" class="form-control" id="full-name" placeholder="Enter your full name">
        </div>
        <div class="mb-3 w-100">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" placeholder="Enter your email address">
        </div>
        <div class="mb-3 w-100">
            <label for="matric-number" class="form-label">Matric number</label>
            <input type="text" class="form-control" id="matric-number" placeholder="Enter your matric number">
        </div>
        <div class="mb-3 w-100">
            <label for="complaint" class="form-label">Complaint content</label>
            <textarea class="form-control" id="complaint" rows="3" placeholder="Complaint content"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit complaint</button>
    </form>
</main>
<?php
require_once("includes/Footer.php");
