<?php
function handleLoginRequest() {
    if (isset($_POST["submit"])) {
        $username = $_POST["username"];
        $pwd = $_POST["pwd"];


        if (emptyInputLogin($username, $pwd) !== false) {
            header("location: ../review.php?loginError=emptyInput");
            exit();
        }
        
        loginUser($username, $pwd);
    } else {
        header("location: ../review.php");
        exit();
    }
}
?>