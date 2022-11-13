<?php
ob_start();
session_start();
require_once("db.php");

function sendComplain() {
    $conn = dbConnect();

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
                return "<div class='alert alert-danger' role='alert'>All fields are required!</div>";
            }
        }

        // Makes sure email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "<div class='alert alert-danger' role='alert'>Invalid email address!</div>";
        }

        // Send an email to the user
        $to = $email;
        $subject = "Complaint Received";
        $message = "Your complaint has been received. We will get back to you as soon as possible.";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
        $headers .= "From: Exams and Records Unit <ojimahwisdom01@gmail.com>";

        if (mail($to, $subject, $message, $headers)) {
            $sql = "INSERT INTO complains (full_name, email, matric_no, complaint_subject, complaint) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $fullName, $email, $matricNumber, $complaintSubject, $complaint);
            $stmt->execute();

            echo "<div class='alert text-bg-success' role='alert'>Complaint sent successfully!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Complaint not sent! Please try again later</div>";
        }

    } else {
        echo "<div class='alert alert-info text-center'>Fill in this form to file your complain. Please be respectful in the things you say and do not send spam messages.</div>";
    }
}

function login()
{
    $conn = dbConnect();

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
        $stmt = $conn->prepare($sql);
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
    $conn = dbConnect();

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
        $stmt = $conn->prepare($sql);
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