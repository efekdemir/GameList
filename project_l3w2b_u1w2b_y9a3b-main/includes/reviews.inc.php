<?php
function handleInsertReviewRequest() {

 
    global $db_conn;

    $gameID = getGameID($_POST['insGameName']);

    if (!$gameID) {
        header("location: ../review.php?reviewError=invalidGameName");
        exit();
    }
    $tuple = array (
        ":bind1" => uniqid(),
        ":bind2" => $gameID,
        ":bind3" => $_POST['insUserID'],
        ":bind4" => $_POST['insText'],
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into review values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
    if (OCICommit($db_conn)) {
        header("location: ../review.php?reviewInsertError=none");
        exit();
    }
}

function getGameID($gameName) {
    global $db_conn;
    $stmt = executePlainSQL("SELECT gameID FROM game WHERE gameName='" . $gameName . "'");
   
    if (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
        return $row[0];
    } else {
        return false;
    }
}


function handleUpdateReviewRequest() {
    global $db_conn;

    $review_id = $_POST['reviewid'];
    $game_id = $_POST['gameid'];
    $new_review = $_POST['newReview'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE review SET review='" . $new_review . "' WHERE reviewid='" . $review_id . "' AND gameid='" . $game_id . "'");
    if (OCICommit($db_conn)) {
        header("location: ../review.php?reviewUpdateError=none");
        exit();
    }
}

function handleDeleteReviewRequest() {
    global $db_conn;

    $game_id = $_POST['gameid'];
    $review_id = $_POST['reviewid'];

    executePlainSQL("DELETE FROM review WHERE reviewid='" . $review_id . " 'AND gameid='" . $game_id . "'");
    OCICommit($db_conn);

}

function handleCountReviewRequest() {
    global $db_conn;
    $game_id = $_POST['gameid'];
   
    $result = executePlainSQL("SELECT Count(*) FROM review WHERE gameid='" . $game_id . "'");

    if (($row = oci_fetch_row($result)) != false) {
        echo "<br> The number of reviews in this game: " . $row[0] . "<br>";
    }
}

function handleDisplayReviewRequest() {
    global $db_conn;


    $result = executePlainSQL("SELECT * FROM review");

    printReviewResult($result);
}


function printReviewResult($result) { 
    define("c", "<center>");
    define("d", "</center>");
    define("td", "</td><td>");
    
    echo "<br>Retrieved data from table reviewTable:<br>";
    echo "<table>";
    echo "<tr><th><center>Review ID</center></th><th><center>Game ID</center></th><th><center>Review</center></th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . c . $row["REVIEWID"] . d . td . c .  $row["GAMEID"] . d . td . c . $row["TEXT"] . d . "</td></tr>"; //or just use "echo $row[0]" 
    }

    echo "</table>";
}


// function handleResetRequest() {
//     global $db_conn;
//     // Drop old table
//     executePlainSQL("DROP TABLE reviewTable");

//     // Create new table
//     echo "<br> creating new table <br>";
//     executePlainSQL("CREATE TABLE reviewTable (reviewID int, gameID int, review varchar(50) NOT NULL, FOREIGN KEY(gameID) REFERENCES gameTable, PRIMARY KEY(gameID, reviewID))");
//     OCICommit($db_conn);
// }
?>