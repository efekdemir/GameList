<?php
function handleResetRequest() {
    global $db_conn;
    // Drops all tables
    
    executePlainSQL("DROP TABLE review");        
    executePlainSQL("DROP TABLE game");

    // Create new table
    echo "<br> Resetting GameTable <br>";
    executePlainSQL("CREATE TABLE game (id int char(100) KEY, name char(30) NOT NULL, avgScore float NOT NULL, genre varchar(30), gameStudio varchar(30) NOT NULL, adminID varchar(100) NOT NULL");
    OCICommit($db_conn);
}

function handleInsertGameRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => uniqid(),
        ":bind2" => $_POST['insAdminID'],
        ":bind3" => $_POST['insStudio'],
        ":bind4" => $_POST['insName'],
        ":bind5" => $_POST['insGenre'],
        ":bind6" => $_POST['insScore']
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into game values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
  
    if (OCICommit($db_conn)) {
        header("location: ../admin.php?gameInsertError=none");
        exit();
    }
   
}


function handleUpdateGameNameRequest() {
    global $db_conn;

    $game_id = $_POST['gameID'];
    $new_name = $_POST['newName'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE game SET gamename='" . $new_name . "' WHERE gameid='" . $game_id . "'");
    if (OCICommit($db_conn)) {
        header("location: ../admin.php?gameUpdateError=none");
        exit();
    }
}

function handleDeleteRequest() {
    global $db_conn;

    $gameID = $_POST['gameID'];

    executePlainSQL("DELETE FROM game WHERE gameid='" . $gameID . "'");
    if (OCICommit($db_conn)) {
        header("location: ../admin.php?gameDeleteError=none");
        exit();
    }
}
?>