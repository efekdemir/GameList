<?php
$success = True; 
$db_conn = NULL; 
$show_debug_alert_messages = False; 

function executeBoundSQL($cmdstr, $list) {
    /* Bound is useful in protecting against SQL injection. */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            OCIBindByName($statement, $bind, $val);
            unset ($val);
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); 
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}

function executePlainSQL($cmdstr) { 
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr); 

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); 
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function connectToDB() {
 
    global $db_conn;
    $db_conn = OCILogon("ora_bozkan01", "a85984987", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected"); 
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); 
        echo htmlentities($e['message']);
        return false;
    }
}
function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

require_once 'user.inc.php';
require_once 'admin.inc.php';
require_once 'signup.inc.php';
require_once 'login.inc.php';
require_once 'reviews.inc.php';


function handlePOSTRequest() {
  
    if (connectToDB()) {
        if (array_key_exists('resetTablesRequest', $_POST)) {
            handleResetRequest();
        } else if (array_key_exists('updateReviewRequest', $_POST)) {
            handleUpdateReviewRequest();
        } else if (array_key_exists('insertGameRequest', $_POST)) {
            handleInsertGameRequest();
        } else if (array_key_exists('deleteReviewRequest', $_POST)) {
            handleDeleteReviewRequest();
        } else if (array_key_exists('loginAdminRequest', $_POST)) {
            handleLoginAdminRequest();
        } else if (array_key_exists('insertUserRequest', $_POST)) {
            handleInsertUserRequest();
        } else if (array_key_exists('loginUserRequest', $_POST)) {
            handleLoginRequest();
        } else if (array_key_exists('insertReviewRequest', $_POST)) {
            handleInsertReviewRequest();
        } else if (array_key_exists('updateGameNameRequest', $_POST)) {
            handleUpdateGameNameRequest();
        } else if (array_key_exists('deleteGameRequest', $_POST)) {
            handleDeleteRequest();
        }
        disconnectFromDB();
    }
}


// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('displayTuples', $_GET)){
            handleDisplayRequest();
        } else if (array_key_exists('maxTuples', $_GET)){
            handleMaxRequest();
        } else if (array_key_exists('minTuples', $_GET)){
            handleMinRequest();
        } else if (array_key_exists('displayFilter', $_GET)){
            handleFilterDisplayRequest();
        } else if (array_key_exists('countTupleReviewRequest', $_GET)) {
            handleCountReviewRequest();
        } else if (array_key_exists('displayReviewTupleRequest', $_GET)) {
            handleDisplayReviewRequest();
        }

        disconnectFromDB();
    }
}

if (isset($_POST['submit']) 
        || isset($_POST['reset']) 
        || isset($_POST['updateSubmit']) 
        || isset($_POST['deleteGameSubmit']) 
        || isset($_POST['insertSubmit'])
        || isset($_POST['adminLoginSubmit'])) {

    handlePOSTRequest();
} else if (isset($_GET['countTupleRequest']) 
        || isset($_GET['maxTupleRequest']) 
        || isset($_GET['minTupleRequest']) 
        || isset($_GET['displayFilterRequest'])
        || isset($_GET['displayStudioRequest'])
        || isset($_GET['displayTopGamesRequest']) 
        || isset($_GET['countReviewTuples'])
        || isset($_GET['displayReviewTuples'])) {
    handleGETRequest();
} 