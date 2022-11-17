<?php
ob_start();
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once("db.php");

function sendComplain() {
    $con = dbConnect();

    if (isset($_POST['submit-complain'])) {
        $fullName = ucwords($_POST['full-name']);
        $email = strtolower($_POST['email']);
        $matricNumber = strtoupper($_POST['matric-number']);
        $complaintSubject = ucfirst($_POST['complaint-subject']);
        $complaint = ucfirst($_POST['complaint']);

        // Makes sure all fields are filled
        $fields = [
            $fullName,
            $email,
            $matricNumber,
            $complaintSubject,
            $complaint
        ];
        foreach($fields as $field) {
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
            $sql = "INSERT INTO complains (full_name, email, matric_no, complaint_subject, complaint) VALUES (?, ?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("sssss", $fullName, $email, $matricNumber, $complaintSubject, $complaint);
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

function register() {
    $con = dbConnect();

    if(isset($_POST['register'])) {
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

function resetPassword() {
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

function countTotalComplains () {
    $con = dbConnect();

    $sql = "SELECT id FROM complains";
    $stmt = $con->query($sql);

    return $stmt->num_rows;
}

function countPendingComplains () {
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

function viewComplainDetails() {
    $con = dbConnect();

    $sql = "SELECT * FROM complains WHERE `status` = 'pending'";
    $stmt = $con->query($sql);

    return $stmt;
}

function setComplainAsResolved () {
    $con = dbConnect();

    if (isset($_POST['submit'])) {
        $complainID = $_GET['complain_id'];
        $finalRemarks = $_POST['remark'];

        if (empty($finalRemarks)) {
            echo "<div class='alert alert-danger text-center' role='alert'>Please type in your final remarks</div>";

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
        $subject = "Complaint Resolved";
        $message = "<p>Dear {$details->full_name}, it is my pleasure to inform you that your complain with subject <b>{$details->complaint_subject}</b> dated {$details->date} has been successfully resolved. We thank you for your patience! Below is a the remark from the exams and record officer</p>
    <p>{$finalRemarks}</p>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Exams and Records Unit <ojimahwisdom01@gmail.com>";

        if (mail($to, $subject, $message, $headers)) {
            $sql = "UPDATE complains SET `status` = 'resolved' WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $complainID);
            $stmt->execute();

            echo "<div class='alert text-bg-success text-center' role='alert'>Complain was successfully resolved!</div>";
        } else {
            echo "<div class='alert alert-danger text-center' role='alert'>Complaint was not resolved! Please try again later</div>";
        }
    }
}