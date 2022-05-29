<?php

include_once 'redirect.php';
if (checkAuth()) {
    header('Location: home.php');
    exit;
}

// LOGIN PROCEDURE ----------------------------------------------------------------------

if (!empty($_POST["l_username"]) && !empty($_POST["l_password"])) {
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['name']) or die(mysqli_error($conn));

    $l_username = mysqli_real_escape_string($conn, $_POST['l_username']);
    $l_password = mysqli_real_escape_string($conn, $_POST['l_password']);

    $query = "SELECT id,username,password FROM users WHERE username = '" . $l_username . "'";
    $res = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if (mysqli_num_rows($res) > 0) {
        $entry = mysqli_fetch_assoc($res);

        if (password_verify($_POST['l_password'], $entry['password'])) {
            $_SESSION["username"] = $entry["username"];
            $_SESSION["id"] = $entry["id"];

            header("Location: home.php");
            mysqli_free_result($res);
            mysqli_close($conn);
            exit;
        }
    }
    $l_error = "Check your credentials.";
} else if (!empty($_POST["l_username"]) || !empty($_POST["l_password"])) {
    $l_error = "Please fill out both fields.";
}


// SIGNUP PROCEDURE ---------------------------------------------------------------------

if (
    !empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["email"]) && !empty($_POST["s_username"]) &&
    !empty($_POST["s_password"]) && !empty($_POST["s_confirm_password"])
) {
    $s_error = array();
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['name']) or die(mysqli_error($conn));


    if (!preg_match('/^[a-zA-Z0-9_]{1,16}$/', $_POST['s_username'])) {
        $s_error[] = "Invalid username";
    } else {
        $s_username = mysqli_real_escape_string($conn, $_POST['s_username']);

        $query = "SELECT username FROM users WHERE username = '" . $s_username . "'";
        $res = mysqli_query($conn, $query);

        if (mysqli_num_rows($res) > 0) {
            $s_error[] = "This username is already taken";
        }
    }

    if (strlen($_POST["s_password"]) < 8) {
        $s_error[] = "Password is too weak: make it at least 8 characters";
    }

    if (strcmp($_POST["s_password"], $_POST["s_confirm_password"]) != 0) {
        $s_error[] = "Passwords don't match";
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $s_error[] = "Invalid email address";
    } else {
        $email = mysqli_real_escape_string($conn, strtolower($_POST['email']));

        $res = mysqli_query($conn, "SELECT email FROM users WHERE email = '" . $email . "'");
        if (mysqli_num_rows($res) > 0) {
            $s_error[] = "This email is already taken";
        }
    }

    if (count($s_error) == 0) {
        $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
        $s_password = mysqli_real_escape_string($conn, $_POST['s_password']);
        $s_password = password_hash($s_password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users(username, password, firstname, lastname, email) VALUES('$s_username', '$s_password', '$firstname', '$lastname', '$email')";

        if (mysqli_query($conn, $query)) {
            $_SESSION["username"] = $_POST["s_username"];
            $_SESSION["id"] = mysqli_insert_id($conn);
            mysqli_close($conn);
            header("Location: home.php");
            exit;
        } else {
            $s_error[] = "Cannot communicate with database!";
        }
    }

    mysqli_close($conn);
} else if (isset($_POST["username"])) {
    $s_error = array("Riempi tutti i campi");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./style/landing.css">
    <script src="./scripts/landing.js" defer></script>

    <title>Welcome</title>
</head>

<body>

    <h2>Homework 1 - Danilo Caruso</h2>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form name="signup_form" method="post">
                <h1>Welcome!</h1>
                <span>Let us know more about yourself</span>

                <?php

                if (isset($s_error)) {
                    echo "<span class='error'>$s_error</span>";
                }

                ?>

                <div id="fullname">
                    <input name="firstname" type="text" placeholder="First name" required />
                    <input name="lastname" type="text" placeholder="Last name" required />
                </div>
                <div id="err_name" class="signup_error hidden">Invalid name(s) (16 characters max.)</div>

                <input name="email" type="email" placeholder="Email" required />
                <div id="err_email" class="signup_error hidden">Invalid email address</div>

                <input name="s_username" type="text" placeholder="Username" required />
                <div id="err_user" class="signup_error hidden">Invalid username (16 characters max.)</div>

                <div id="passwords">
                    <input name="s_password" type="password" placeholder="Password" required />
                    <input name="s_confirm_password" type="password" placeholder="Confirm password" required />
                </div>
                <div id="err_pass" class="signup_error hidden">Password is too weak: make it at least 8 characters</div>
                <div id="err_match" class="signup_error hidden">Passwords don't match</div>

                <input class="btn" type="submit" value="Sign up">
                <div id="err_final" class="signup_error hidden">Something's wrong; double check your credentials.</div>

            </form>
        </div>
        <div class="form-container sign-in-container">
            <form name='login_form' method='post'>
                <h1>Welcome back!</h1>
                <span>Log in with your credentials</span>

                <?php

                if (isset($l_error)) {
                    echo "<span class='error'>$l_error</span>";
                }

                ?>

                <input name="l_username" type="text" placeholder="Username" />
                <input name="l_password" type="password" placeholder="Password" />
                <input class="btn" type="submit" value="Sign in">
                <div class="login_error hidden">Please fill out both fields.</div>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Already have an account?</h1>
                    <p>Switch to the log-in page.</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Don't have an account?</h1>
                    <p>Create one right away!</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>