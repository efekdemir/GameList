<?php

function handleInsertUserRequest() {
    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $pwd = $_POST["pwd"];
        $pwdAgain = $_POST["pwdAgain"];

        if (emptyInputSignup($username, $email, $pwd, $pwdAgain) !== false) {
            header("location: ../review.php?signuperror=emptyInput");
            exit();
        }

        if (invalidUsername($username) !== false) {
            header("location: ../review.php?signuperror=invalidUsername");
            exit();
        }

        if (invalidEmail($email) !== false) {
            header("location: ../review.php?signuperror=invalidEmail");
            exit();
        }

        if (pwdMatch($pwd, $pwdAgain) !== false) {
            header("location: ../review.php?signuperror=pwdNoMatch");
            exit();
        }

        if (userExists($username) !== false) {
            header("location: ../review.php?signuperror=usernameTaken");
            exit();
        }

        createUser($username, $email, $pwd);
    } else {
        header("location: ../review.php");
        exit();
    }
}
