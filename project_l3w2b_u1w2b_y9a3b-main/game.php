<!--
  Created by Efe Demir -->

<!DOCTYPE html>
<html lang="en">

<head>
    <title> GameSite - Page One</title>
</head>

<body>
    <h1> Welcome to the Game Page! </h1>
    
    
    <!-- <h2>Reset Tables</h2>
    <p>Warning! All tables will be reset if you press Reset!</p>

    <form method="POST" action="game.php">
        <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
        <p><input type="submit" value="Reset" name="reset"></p>
    </form>

    <hr /> -->

    <h2>Add New Game</h2>
    <form method="POST" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
        Game ID: <input type="text" name="insNo"> <br /><br />
        Admin ID: <input type="text" name="insAdmin"> <br /><br />
        Game Studio: <input type="text" name="insStudio"> <br /><br />
        Name: <input type="text" name="insName"> <br /><br />
        Genre: <input type="text" name="insGenre"> <br /><br />
        Average Score: <input type="text" name="insScore"> <br /><br />

        <input type="submit" value="Add" name="insertSubmit"></p>
    </form>

    <hr />

    <h2>Update Game Name</h2>
    <p>If you enter an invalid Game ID, nothing will happen.</p>

    <form method="POST" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
        Game ID: <input type="text" name="id"> <br /><br />
        New Name: <input type="text" name="newName"> <br /><br />

        <input type="submit" value="Update" name="updateSubmit"></p>
    </form>

    <hr />


    <h2>Delete Game</h2>
    <p>If you enter an invalid Game ID, nothing will happen.</p>

    <form method="POST" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
        Game ID: <input type="text" name="gameID"> <br /><br />

        <input type="submit" value="Delete" name="deleteSubmit"></p>
    </form>

    <hr />


    <h2>Count the number of games in database</h2>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="countTupleRequest" name="countTupleRequest">
        <input type="submit" value="Count" name="countTuples"></p>
    </form>

    <h2>Find the highest rated game in database</h2>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="maxTupleRequest" name="maxTupleRequest">
        <input type="submit" value="Find" name="maxTuples"></p>
    </form>

    <h2>Find the lowest rated game in database</h2>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="minTupleRequest" name="minTupleRequest">
        <input type="submit" value="Find" name="minTuples"></p>
    </form>

    <h2>Display the games in database</h2>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
        <input type="submit" value="Display" name="displayTuples"></p>
    </form>

    <h2>Filtered Score Display</h2>
    <p>Let's you see the games with average scores higher than the input.</p>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="displayFilterRequest" name="displayFilterRequest">
        Score: <input type="text" name="score"> <br /><br />
        <input type="submit" value="Display" name="displayFilter"></p>
    </form>


    <h2>Filtered Game Studio Display</h2>
    <p>Let's you see the average score of the games made by Game Studios that have more than X games.</p>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="displayStudioRequest" name="displayStudioRequest">
        Number of Games: <input type="text" name="number"> <br /><br />
        <input type="submit" value="Display" name="displayStudio"></p>
    </form>

    <h2>Filtered Genre Display</h2>
    <p>Let's you see the average score of the games in a certain genre that have more than X games.</p>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="displayGenreRequest" name="displayGenreRequest">
        Number of Games: <input type="text" name="number"> <br /><br />
        <input type="submit" value="Display" name="displayGenre"></p>
    </form>

    <h2>Best Game Studio</h2>
    <p>Let's you see the best game studio with the highest combined average game scores.</p>
    <form method="GET" action="game.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="studioRequest" name="studioRequest">
        <input type="submit" value="Find" name="studioReq"></p>
    </form>

    <h2>Go to the Review Page</h2>
    <form method="GET" action="review.php">
        <!--refresh page when submitted-->
        <input type="submit" value="Go"></p>
    </form>

    <hr />
    <hr />

    <?php

    $success = True;
    $db_conn = NULL;
    $show_debug_alert_messages = False;

    define("c", "<center>");
    define("d", "</center>");
    define("td", "</td><td>");

    function debugAlertMessage($message)
    {
        global $show_debug_alert_messages;

        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }

    function executePlainSQL($cmdstr)
    {
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

    function executeBoundSQL($cmdstr, $list)
    {
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
                unset($val);
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

    function printResult($result)
    {

        echo "<br>Retrieved games from the game database:<br>";
        echo "<table>";
        echo "<tr><th><center>Game ID</center></th><th><center>Admin ID</center></th><th><center>Name</center></th><th><center>Average Score</center></th>
                    <th><center>Genre</th></center><th><center>Game Studio</center></th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . c . $row["GAMEID"] . d . td . c . $row["ADMINID"] . d . td . c .  $row["GAMENAME"] . d . td . c . $row["AVGSCORE"] . d . td . c .
                $row["GENRE"] . d . td . c . $row["STUDIONAME"] . d . "</td></tr>"; //or just use "echo $row[0]" 
        }

        echo "</table>" . "<br>" . "<hr />";
    }

    function printResult2($result)
    {
        echo "<br>Retrieved from the game database:<br>";
        echo "<table>";
        echo "<tr><th><center>Game Studio </center></th><th><center> Average Score</center></th>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . c . $row[0] . d . td . c . $row[1] . d . "</td></tr>";
        }

        echo "</table>" . "<br>" . "<hr />";
    }

    function printResult3($result)
    {
        echo "<br>Retrieved from the game database:<br>";
        echo "<table>";
        echo "<tr><th><center>Genre </center></th><th><center> Average Score</center></th>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . c . $row[0] . d . td . c . $row[1] . d . "</td></tr>";
        }

        echo "</table>" . "<br>" . "<hr />";
    }

    function connectToDB()
    {
        global $db_conn;


        $db_conn = OCILogon("ora_edemir1", "a88882931", "dbhost.students.cs.ubc.ca:1522/stu");

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

    function disconnectFromDB()
    {
        global $db_conn;

        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }

    // function handleResetRequest()
    // {
    //     global $db_conn;
    //     // Drops all tables
        
    //     executePlainSQL("DROP TABLE gameStudio");
    //     executePlainSQL("DROP TABLE review");
    //     executePlainSQL("DROP TABLE game");

    //     // Create new table
    //     echo "<br> Resetting GameTable <br>";
    //     executePlainSQL("CREATE TABLE game (gameID int, adminID int, studioName varchar(100) NOT NULL, gameName varchar(100) NOT NULL, genre varchar(100), avgScore float,
    //                              FOREIGN KEY(studioName) REFERENCES gameStudio(studioName) ON DELETE CASCADE, 
    //                             CONSTRAINT game_score CHECK (avgScore <= 5 AND avgScore >= 0), CONSTRAINT pk_Game PRIMARY KEY(gameID))");
    //     OCICommit($db_conn);
    // }


    function handleInsertRequest()
    {
        global $db_conn;

        //Getting the values from user and insert data into the table
        $tuple = array(
            ":bind1" => $_POST['insNo'],
            ":bind2" => $_POST['insAdmin'],
            ":bind3" => $_POST['insStudio'],
            ":bind4" => $_POST['insName'],
            ":bind5" => $_POST['insGenre'],
            ":bind6" => $_POST['insScore'],
        );

        $alltuples = array(
            $tuple
        );

        executeBoundSQL("insert into game values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6)", $alltuples);
        OCICommit($db_conn);
    }

    function handleUpdateRequest()
    {
        global $db_conn;

        $game_id = $_POST['id'];
        $new_name = $_POST['newName'];

        // you need the wrap the old name and new name values with single quotations
        executePlainSQL("UPDATE game SET gameName='" . $new_name . "' WHERE gameID='" . $game_id . "'");
        OCICommit($db_conn);
    }

    function handleDeleteRequest()
    {
        global $db_conn;

        $gameID = $_POST['gameID'];

        executePlainSQL("DELETE FROM game WHERE gameID=" . $gameID);
        OCICommit($db_conn);
    }

    function handleCountRequest()
    {
        global $db_conn;

        $result = executePlainSQL("SELECT Count(*) FROM game");

        if (($row = oci_fetch_row($result)) != false) {
            echo "<br> The number of games in database: " . $row[0] . "<br>" . "<br>" . "<hr />";
        }
    }

    function handleStudioRequest()
    {
        global $db_conn;

        $result =  executePlainSQL("
            SELECT g.studioName, AVG(avgScore)
            FROM game g, gameStudio s
            WHERE g.studioName = s.studioName
            GROUP BY g.studioName 
            HAVING AVG(avgScore) >= ALL (SELECT AVG(s.avgScore)
                                         FROM game s
                                         GROUP BY s.studioName)");

        echo "<table>";
        echo "<tr><th>Game Studio</th><th>Average Score</th></tr>";
        while ($row = OCI_fetch_row($result)) {

            echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
        }
        echo "</table>";
    }


    function handleMaxRequest()
    {
        global $db_conn;

        $result = executePlainSQL("SELECT gameName, avgScore FROM game WHERE avgScore = (SELECT MAX(avgScore) from game)");

        if (($row = oci_fetch_row($result)) != false) {
            echo "<br> The highest scoring game in the database is " . "<b>" . $row[0] . "</b>" . " with a score of: " . "<b>" . $row[1] . "</b>";
        }
    }



    function handleMinRequest()
    {
        global $db_conn;

        $result = executePlainSQL("SELECT gameName, avgScore FROM game WHERE avgScore = (SELECT MIN(avgScore) from game)");

        if (($row = oci_fetch_row($result)) != false) {
            echo "<br> The lowest scoring game game in the database is " . "<b>" . $row[0] . "</b>" . " with a score of: " . "<b>" . $row[1] . "</b>";
        }
    }

    function handleDisplayRequest()
    {
        global $db_conn;

        $result = executePlainSQL("SELECT * FROM game ORDER BY gameID");

        printResult($result);
    }

    function handleFilterDisplayRequest()
    {
        global $db_conn;

        $score = $_GET["score"];

        $result = executePlainSQL("SELECT * FROM game WHERE avgScore>'" . $score . "'"  . "ORDER BY gameID");

        printResult($result);
    }

    function handleStudioDisplayRequest()
    {
        global $db_conn;

        $number = $_GET["number"];

        $result = executePlainSQL("SELECT studioName, AVG(avgScore) FROM game GROUP BY studioName HAVING COUNT(studioName) >" . $number);

        printResult2($result);
    }

    function handleGenreDisplayRequest()
    {
        global $db_conn;

        $number = $_GET["number"];

        $result = executePlainSQL("SELECT genre, AVG(avgScore) FROM game GROUP BY genre HAVING COUNT(genre) >" . $number);

        printResult3($result);
    }

    // HANDLE ALL POST ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('resetTablesRequest', $_POST)) {
                handleResetRequest();
            } else if (array_key_exists('updateQueryRequest', $_POST)) {
                handleUpdateRequest();
            } else if (array_key_exists('insertQueryRequest', $_POST)) {
                handleInsertRequest();
            } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                handleDeleteRequest();
            }

            disconnectFromDB();
        }
    }


    // HANDLE ALL GET ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('countTuples', $_GET)) {
                handleCountRequest();
            } else if (array_key_exists('displayTuples', $_GET)) {
                handleDisplayRequest();
            } else if (array_key_exists('maxTuples', $_GET)) {
                handleMaxRequest();
            } else if (array_key_exists('minTuples', $_GET)) {
                handleMinRequest();
            } else if (array_key_exists('displayFilter', $_GET)) {
                handleFilterDisplayRequest();
            } else if (array_key_exists('displayStudio', $_GET)) {
                handleStudioDisplayRequest();
            } else if (array_key_exists('displayGenre', $_GET)) {
                handleGenreDisplayRequest();
            } else if (array_key_exists('studioReq', $_GET)) {
                handleStudioRequest();
            }

            disconnectFromDB();
        }
    }

    if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {
        handlePOSTRequest();
    } else if (isset($_GET['countTupleRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['displayTupleRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['maxTupleRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['minTupleRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['displayFilterRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['displayStudioRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['displayGenreRequest'])) {
        handleGETRequest();
    } else if (isset($_GET['studioRequest'])) {
        handleGETRequest();
    }
    ?>
</body>

</html>