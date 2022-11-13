<?php
require_once("includes/functions.php");
require_once("includes/Header.php");
?>
<main class="form-container container pt-4 pb-4">
    <form method="POST">
        <img class="mb-4 text-center d-block mx-auto" src="assets/img/favicon.ico" alt="" width="72" height="57">

        <?= sendComplain() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="full-name" class="form-label">Full name</label>
                <input type="text" class="form-control" id="full-name" name="full-name" placeholder="Enter your full name">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address">
            </div>
            <div class="col-md-6">
                <label for="matric-number" class="form-label">Matric number</label>
                <input type="text" class="form-control" id="matric-number" name="matric-number" placeholder="Enter your matric number">
            </div>
            <div class="col-md-6">
                <label for="complaint-subject" class="form-label">Complaint subject</label>
                <input type="text" class="form-control" id="complaint-subject" name="complaint-subject" placeholder="Complaint subject">
            </div>
            <div class="col-md-12 mb-3">
                <label for="complaint" class="form-label">Complaint content</label>
                <textarea class="form-control" id="complaint" name="complaint" rows="5" placeholder="Complaint content"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="submit-complain">Submit complaint</button>
    </form>
</main>
<?php
require_once("includes/Footer.php");
