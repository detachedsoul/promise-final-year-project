<?php
ob_start();
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once("db.php");

function sendComplain()
{
    $con = dbConnect();

    if (isset($_POST['submit-complain'])) {
        $fullName = ucwords($_POST['full-name']);
        $email = strtolower($_POST['email']);
        $matricNumber = strtoupper($_POST['matric-number']);
        $complaintSubject = ucfirst($_POST['complaint-subject']);
        $complaint = ucfirst($_POST['complaint']);
        $date = date("jS F, Y");

        // Makes sure all fields are filled
        $fields = [
            $fullName,
            $email,
            $matricNumber,
            $complaintSubject,
            $complaint
        ];
        foreach ($fields as $field) {
            if (empty($field)) {
                return "<div class='alert alert-danger text-center' role='alert'>All fields are required!</div>";
            }
        }

        // Makes sure email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<div class='alert alert-danger text-center' role='alert'>Invalid email address!</div>";
        }

        // Send an email to the user
        $to = $email;
        $subject = "Complaint Received";
        $message = "Your complain has been received. You will be notified when your issue is resolved.";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Exams and Records Unit <ojimahwisdom01@gmail.com>";

        if (mail($to, $subject, $message, $headers)) {
            $sql = "INSERT INTO complains (`full_name`, `email`, `matric_no`, `complaint_subject`, `complaint`, `date`) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssssss", $fullName, $email, $matricNumber, $complaintSubject, $complaint, $date);
            $stmt->execute();

            echo "<div class='alert text-bg-success text-center' role='alert'>Complaint sent successfully!</div>";
        } else {
            echo "<div class='alert alert-danger text-center' role='alert'>Complaint not sent! Please try again later</div>";
        }
    } else {
        echo "<div class='alert alert-info text-center'>Fill in this form to file your complain. Please be respectful in the things you say and do not send spam messages.</div>";
    }
}

function login()
{
    $con = dbConnect();

    if (isset($_POST['login'])) {
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];

        // Makes sure all fields are filled
        $fields = [
            $email,
            $password
        ];
        foreach ($fields as $field) {
            if (empty($field)) {
                return "<div class='alert alert-danger text-center' role='alert'>All fields are required!</div>";
            }
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();

        if ($user) {
            if (password_verify($password, $user->password)) {
                echo "<div class='alert text-bg-success text-center' role='alert'>Login successful!</div>";

                $_SESSION['user'] = $user->name;
                $_SESSION['id'] = $user->id;
                header("Refresh: 3, admin/index.php");
            } else {
                return "<div class='alert alert-danger text-center' role='alert'>Invalid password!</div>";
            }
        } else {
            return "<div class='alert alert-danger text-center' role='alert'>Invalid email address!</div>";
        }
    } else {
        echo "<div class='alert alert-info text-center'>Login to your dashboard to view your complains.</div>";
    }
}

function register()
{
    $con = dbConnect();

    if (isset($_POST['register'])) {
        $fullName = ucwords($_POST['full-name']);
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];

        // Makes sure all fields are filled
        $fields = [
            $fullName,
            $email,
            $password,
        ];
        foreach ($fields as $field) {
            if (empty($field)) {
                return "<div class='alert alert-danger text-center' role='alert'>All fields are required!</div>";
            }
        }

        // Makes sure email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<div class='alert alert-danger text-center' role='alert'>Invalid email address!</div>";
        }

        // Hash password
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (`name`, `email`, `password`) VALUES (?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $fullName, $email, $password);
        $stmt->execute();

        if ($stmt) {
            echo "<div class='alert text-bg-success text-center' role='alert'>Registration successful! You can now login.</div>";

            header("Refresh: 3; login.php");
        } else {
            echo "<div class='alert alert-danger text-center' role='alert'>Registration failed! Please try again later.</div>";
        }
    } else {
        echo "<div class='alert alert-info text-center'>Register to view complain.</div>";
    }
}

function resetPassword()
{
    $con = dbConnect();

    if (isset($_POST['reset-password'])) {
        $email = strtolower($_POST['email']);

        // Makes sure all fields are filled
        $fields = [
            $email,
        ];
        foreach ($fields as $field) {
            if (empty($field)) {
                return "<div class='alert alert-danger text-center' role='alert'>All fields are required!</div>";
            }
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();

        if ($user) {
            $password = bin2hex(random_bytes(5));

            // Send an email to the user
            $to = $email;
            $subject = "Password Reset";
            $message = "Your password has been reset. Use {$password} to login to your account. You can change this later in your dashboard.";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: Exams and Records Unit <ojimahwisdom01@gmail.com>";

            if (mail($to, $subject, $message, $headers)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $sql = "UPDATE users SET `password` = ? WHERE email = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ss", $hashedPassword, $email);
                $stmt->execute();

                echo "<div class='alert text-bg-success text-center' role='alert'>Password reset successful! Check your email for your new password.</div>";
            } else {
                echo "<div class='alert alert-danger text-center' role='alert'>Password reset failed! Please try again later.</div>";
            }
        } else {
            return "<div class='alert alert-danger text-center' role='alert'>Invalid email address!</div>";
        }
    } else {
        echo "<div class='alert alert-info text-center'>Enter your email address to reset your password.</div>";
    }
}

function countTotalComplains()
{
    $con = dbConnect();

    $sql = "SELECT id FROM complains";
    $stmt = $con->query($sql);

    return $stmt->num_rows;
}

function countPendingComplains()
{
    $con = dbConnect();

    $sql = "SELECT id FROM complains WHERE `status` = 'pending'";
    $stmt = $con->query($sql);

    return $stmt->num_rows;
}

function countResolvedComplains()
{
    $con = dbConnect();

    $sql = "SELECT id FROM complains WHERE `status` = 'resolved'";
    $stmt = $con->query($sql);

    return $stmt->num_rows;
}

function setComplainAsResolved()
{
    $con = dbConnect();

    if (isset($_POST['submit'])) {
        $complainID = $_GET['id'];
        $finalRemarks = $_POST['remark'];
        $date = date("jS F, Y");

        if (empty($finalRemarks)) {
            echo "<div class='alert alert-danger text-center modal-title' role='alert'><h5>Please type in your final remarks</h5></div>";

            return;
        }

        // Send a mail to the complainer
        $sql = "SELECT `full_name`, `email`, `complaint_subject`, `date` FROM complains WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $complainID);
        $stmt->execute();
        $result = $stmt->get_result();
        $details = $result->fetch_object();

        $to = $details->email;
        $subject = "Complain Resolved";
        $message = "<p>Dear {$details->full_name}, it is my pleasure to inform you that your complain with subject <b>{$details->complaint_subject}</b> dated {$details->date} has been successfully resolved. We thank you for your patience! Below is a the remark from the exams and record officer:</p>
    <p>{$finalRemarks}</p>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Exams and Records Unit <ojimahwisdom01@gmail.com>";

        if (mail($to, $subject, $message, $headers)) {
            $sql = "UPDATE complains SET `status` = 'resolved', resolved_date = ?, final_remark = ? WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sss", $date, $finalRemarks, $complainID);
            $stmt->execute();

            echo "<div class='alert text-bg-success text-center modal-title' role='alert'><h5>Complain was successfully resolved!</h5></div>";

            header('Refresh: 3, resolved-complains.php');
        } else {
            echo "<div class='alert alert-danger text-center' role='alert modal-title'><h5>Complaint was not resolved! Please try again later</h5></div>";
        }
    } else {
        echo "Complain Details";
    }
}

function dashboardComplains()
{
    $con = dbConnect();

    $sql = "SELECT * FROM complains ORDER BY id LIMIT 5";

    $stmt = $con->query($sql);

    if ($stmt->num_rows < 1) {
        echo "<h3 class='text-center text-danger'>There are no complain(s) yet.</h3>";

        return;
    }
?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle" style="min-width: max-content;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Filled By</th>
                    <th scope="col">Email</th>
                    <th scope="col">Matric No</th>
                    <th scope="col">Complain Subject</th>
                    <th scope="col">Complain</th>
                    <th scope="col">Dated</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($complain = $stmt->fetch_object()) : ?>
                    <tr>
                        <th scope="row">
                            <?= $complain->id ?>
                        </th>
                        <td>
                            <?= $complain->full_name ?>
                        </td>
                        <td>
                            <?= $complain->email ?>
                        </td>
                        <td>
                            <?= $complain->matric_no ?>
                        </td>
                        <td>
                            <?= $complain->complaint_subject ?>
                        </td>
                        <td>
                            <?= substr($complain->complaint, 0, 50) ?>
                        </td>
                        <td>
                            <?= $complain->date ?>
                        </td>
                        <td class="<?= ($complain->status === 'pending') ? 'text-danger' : 'text-success' ?>">
                            <?= ucwords($complain->status) ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href=<?= ($complain->status === 'pending') ? "view-complain.php?id={$complain->id}" : "view-resolved-complain-details.php?id={$complain->id}" ?>>
                                View Complain
                            </a>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
    </div>

    <a class="btn btn-primary mt-4" href="/admin/all-complains.php">
        View All
    </a>
<?php
}

function viewPendingComplains()
{
    $con = dbConnect();

    $sql = "SELECT * FROM complains WHERE `status` = 'pending'";

    $stmt = $con->query($sql);

    if ($stmt->num_rows < 1) {
        echo "<h3 class='text-center text-danger'>There are no complain(s) yet.</h3>";

        return;
    }
?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle" style="min-width: max-content;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Filled By</th>
                    <th scope="col">Email</th>
                    <th scope="col">Matric No</th>
                    <th scope="col">Complain Subject</th>
                    <th scope="col">Complain</th>
                    <th scope="col">Dated</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($complain = $stmt->fetch_object()) : ?>
                    <tr>
                        <th scope="row">
                            <?= $complain->id ?>
                        </th>
                        <td>
                            <?= $complain->full_name ?>
                        </td>
                        <td>
                            <?= $complain->email ?>
                        </td>
                        <td>
                            <?= $complain->matric_no ?>
                        </td>
                        <td>
                            <?= $complain->complaint_subject ?>
                        </td>
                        <td>
                            <?= substr($complain->complaint, 0, 50) ?>
                        </td>
                        <td>
                            <?= $complain->date ?>
                        </td>
                        <td class="<?= ($complain->status === 'pending') ? 'text-danger' : 'text-success' ?>">
                            <?= ucwords($complain->status) ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="view-complain.php?id=<?= $complain->id ?>">
                                View Complain
                            </a>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
    </div>
<?php
}

function viewResolvedComplains()
{
    $con = dbConnect();

    $sql = "SELECT * FROM complains WHERE `status` = 'resolved'";

    $stmt = $con->query($sql);

    if ($stmt->num_rows < 1) {
        echo "<h3 class='text-center text-danger'>There are no resolved complain(s) yet.</h3>";

        return;
    }
?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle" style="min-width: max-content;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Filled By</th>
                    <th scope="col">Email</th>
                    <th scope="col">Matric No</th>
                    <th scope="col">Complain Subject</th>
                    <th scope="col">Complain</th>
                    <th scope="col">Dated</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($complain = $stmt->fetch_object()) : ?>
                    <tr>
                        <th scope="row">
                            <?= $complain->id ?>
                        </th>
                        <td>
                            <?= $complain->full_name ?>
                        </td>
                        <td>
                            <?= $complain->email ?>
                        </td>
                        <td>
                            <?= $complain->matric_no ?>
                        </td>
                        <td>
                            <?= $complain->complaint_subject ?>
                        </td>
                        <td>
                            <?= substr($complain->complaint, 0, 50) ?>
                        </td>
                        <td>
                            <?= $complain->date ?>
                        </td>
                        <td class="<?= ($complain->status === 'pending') ? 'text-danger' : 'text-success' ?>">
                            <?= ucwords($complain->status) ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href="view-resolved-complain-details.php?id=<?= $complain->id ?>">
                                View Complain Details
                            </a>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
    </div>
<?php
}

function viewAllComplains()
{
    $con = dbConnect();

    $sql = "SELECT * FROM complains ORDER BY id";

    $stmt = $con->query($sql);

    if ($stmt->num_rows < 1) {
        echo "<h3 class='text-center text-danger'>There are no complain(s) yet.</h3>";

        return;
    }
?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle" style="min-width: max-content;">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Filled By</th>
                    <th scope="col">Email</th>
                    <th scope="col">Matric No</th>
                    <th scope="col">Complain Subject</th>
                    <th scope="col">Complain</th>
                    <th scope="col">Dated</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($complain = $stmt->fetch_object()) : ?>
                    <tr>
                        <th scope="row">
                            <?= $complain->id ?>
                        </th>
                        <td>
                            <?= $complain->full_name ?>
                        </td>
                        <td>
                            <?= $complain->email ?>
                        </td>
                        <td>
                            <?= $complain->matric_no ?>
                        </td>
                        <td>
                            <?= $complain->complaint_subject ?>
                        </td>
                        <td>
                            <?= substr($complain->complaint, 0, 50) ?>
                        </td>
                        <td>
                            <?= $complain->date ?>
                        </td>
                        <td class="<?= ($complain->status === 'pending') ? 'text-danger' : 'text-success' ?>">
                            <?= ucwords($complain->status) ?>
                        </td>
                        <td>
                            <a class="btn btn-primary" href=<?= ($complain->status === 'pending') ? "view-complain.php?id={$complain->id}" : "view-resolved-complain-details.php?id={$complain->id}" ?>>
                                View Complain
                            </a>
                        </td>
                    </tr>
                <?php
                endwhile;
                ?>
            </tbody>
        </table>
    </div>
<?php
}

function viewResolvedComplainsDetails()
{
    if (!isset($_GET['id'])) {
        header('Location: resolved-complains.php');
    }

    $con = dbConnect();
    $id = $_GET['id'];

    $sql = "SELECT * FROM complains WHERE id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $complainDetails = $result->fetch_object();

    if ($result->num_rows < 1) {
        header('Location: resolved-complains.php');
    }

    if ($complainDetails->status !== 'resolved') {
        header('Location: resolved-complains.php');
    }
?>
    <div class="modal d-block position-static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Complain Details
                        <?= setComplainAsResolved() ?>
                    </h5>
                </div>
                <div class="modal-body row justify-content-between g-4">
                    <div class="col-md-6">
                        <h3 class="h6">
                            ID
                        </h3>
                        <p>
                            <?= $complainDetails->id ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Full Name
                        </h3>
                        <p>
                            <?= $complainDetails->full_name ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Email
                        </h3>
                        <p>
                            <?= $complainDetails->email ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Matric No
                        </h3>
                        <p>
                            <?= $complainDetails->matric_no ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Date Filed
                        </h3>
                        <p>
                            <?= $complainDetails->date ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Complain Subject
                        </h3>
                        <p>
                            <?= $complainDetails->complaint_subject ?>
                        </p>
                    </div>

                    <div class="col-12">
                        <h3 class="h6">
                            Complain Details
                        </h3>
                        <p>
                            <?= $complainDetails->complaint ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Date Resolved
                        </h3>
                        <p>
                            <?= $complainDetails->resolved_date ?>
                        </p>
                    </div>

                    <div class="col-12">
                        <h3 class="h6">
                            Final Remark
                        </h3>
                        <p>
                            <?= $complainDetails->final_remark ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

function viewPendingComplainsDetails()
{
    if (!isset($_GET['id'])) {
        header('Location: /admin');
    }

    $con = dbConnect();
    $id = $_GET['id'];

    $sql = "SELECT * FROM complains WHERE id = ?";

    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $complainDetails = $result->fetch_object();

    if ($result->num_rows < 1) {
        header('Location: /admin');
    }

    if ($complainDetails->status !== 'pending') {
        header('Location: pending-complains.php');
    }
?>
    <form class="modal d-block position-static" method="POST">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <?= setComplainAsResolved() ?>
                </div>
                <div class="modal-body row justify-content-between g-4">
                    <div class="col-md-6">
                        <h3 class="h6">
                            ID
                        </h3>
                        <p>
                            <?= $complainDetails->id ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Full Name
                        </h3>
                        <p>
                            <?= $complainDetails->full_name ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Email
                        </h3>
                        <p>
                            <?= $complainDetails->email ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Matric No
                        </h3>
                        <p>
                            <?= $complainDetails->matric_no ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Date Filed
                        </h3>
                        <p>
                            <?= $complainDetails->date ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <h3 class="h6">
                            Complain Subject
                        </h3>
                        <p>
                            <?= $complainDetails->complaint_subject ?>
                        </p>
                    </div>

                    <div class="col-12">
                        <h3 class="h6">
                            Complain Details
                        </h3>
                        <p>
                            <?= $complainDetails->complaint ?>
                        </p>
                    </div>

                    <div class="col-12">
                        <label for="remark" class="form-label h6">
                            Final Remark
                        </label>
                        <textarea class="form-control w-100" id="remark" name="remark" rows="5" placeholder="Final Remark"></textarea>
                    </div>

                    <div class="col-6">
                        <button class="btn btn-primary" name="submit">
                            Resolve Complain
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    <?php
}
