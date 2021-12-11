<?php
function emptyInputSignup($username, $email, $pwd, $pwdAgain) {
    if (empty($username) || empty($email) || empty($pwd) || empty($pwdAgain)) {
        return true;
    } else {
        return false;
    }
}

function invalidUsername($username) {
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        return true;
    } else {
        return false;
    }
}

function invalidEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function pwdMatch($pwd, $pwdAgain) {
    if ($pwd !== $pwdAgain) {
        return true;
    } else {
        return false;
    }
}

function userExists($username) {
    $stmt = executePlainSQL("SELECT * FROM users WHERE username = '" . $username ."'");
    if (($row = oci_fetch_array($stmt)) !== false) {
        if ($row["USERNAME"]) {
            return $row;
        }
    }
    return false;
}

function createUser($username, $email, $pwd) {
    global $db_conn;
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    // create unique id for users;
    $tuple = array (
        ":bind1" => uniqid(),
        ":bind2" => $username,
        ":bind3" => $email,
        ":bind4" => $hashedPwd
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into users values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
    OCICommit($db_conn);
    header("location: ../review.php?signuperror=none");
    exit();
}


function emptyInputLogin($username, $pwd) {
    if (empty($username) || empty($pwd)) {
        return true;
    } else {
        return false;
    }
}

function loginUser($username, $pwd) {
    $user = userExists($username);
    if ($user === false) {
        header("location: ../review.php?loginError=wrongLogin");
        exit();
    }


    $pwdHashed = $user["PASSWORD"];
    if(password_verify($pwd, $pwdHashed) === false) {
        header("location: ../review.php?loginError=failedLogin");
        exit();
    } else {    
        
        header("location: ../review.php?userID=" . $user["USERID"]);
        exit();
        // $cookie_name = "user";
        // $cookie_value = $user["USERID"];

        // // created seperate func for admin, no need for this
        // // if (adminExists($user["USERID"]) != false) {
        // //     $cookie_value = "admin";
        // // }
  
        // setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

        // if(!isset($_COOKIE[$cookie_name])) {
        //    echo "Cookie named '" . $cookie_name . "' is not set!";
        // } else {
        //     echo "Cookie '" . $cookie_name . "' is set!<br>";
        //     echo "Value is: " . $_COOKIE[$cookie_name];
        // }
       
        // //exit();
        // // $_SESSION["userid"] = $user["USERID"];
        // // $_SESSION["username"] = $user["USERNAME"];
        // // $_SESSION["admin"] = false;
    
    };
}

function handleLoginAdminRequest() {
    $username = $_POST["adminUsername"];
    $pwd = $_POST["adminPassword"];


    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../admin.php?error=emptyInput");
        exit();
    }
    
    loginAdmin($username, $pwd);
}

function adminExists($userid) {
    $stmt = executePlainSQL("SELECT * FROM admins WHERE userid = '" . $userid . "'");
    
    if (($row = oci_fetch_array($stmt)) != false) {
        if ($row["USERID"]) {
            return $row;
        } 
    }
    
    return false;
}

function loginAdmin($username, $pwd) {
    $user = userExists($username);
    if ($user === false) {
        header("location: ../admin.php?error=wrongLogin");
        exit();
    }

    //header("location: ../admin.php?userid=" . $user["USERID"]);
    if (adminExists($user["USERID"]) == false) {
        header("location: ../admin.php?error=wrongUsername");
        exit();
        // session_start();
        // $_SESSION["userid"] = $user["USERID"];
        // $_SESSION["username"] = $user["USERNAME"];
        // $_SESSION["admin"] = true;
    } 
    
    $pwdHashed = $user["PASSWORD"];
    if(password_verify($pwd, $pwdHashed) === false) {
        header("location: ../admin.php?error=wrongPassword");
        exit();
    } else {    
        header("location: ../admin.php?adminID=" . $user["USERID"]);
        exit();
    }
}