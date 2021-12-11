<!--
  Created by Efe Demir -->

  <!DOCTYPE html>
<html lang="en">

    <head>
        <title> ReviewSite - Page Two</title>
    </head>

    <body>
        <h1> Welcome to the Review Page! </h1>
        
        <!-- <h2>Reset Reviews</h2>
        <p>If you reset everything previously, press reset here to recreate the ReviewTable.</p>

        <form method="POST" action="review.php">
           
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr /> -->

        <h2>Add New Review</h2>
        <form method="POST" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Review ID: <input type="text" name="insReview"> <br /><br />
            Game ID: <input type="text" name="insNo"> <br /><br />
            User ID: <input type="text" name="insUser"> <br /><br />
            Review: <input type="text" name="insText"> <br /><br />
            
            <input type="submit" value="Add" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Review</h2>
        <p>If you enter an invalid ID, nothing will happen.</p>

        <form method="POST" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Review ID: <input type="text" name="reviewid"> <br /><br />
            Game ID: <input type="text" name="gameid"> <br /><br />
            New Review: <input type="text" name="newReview"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />
        

        <h2>Delete Review</h2>
        <p>If you enter an invalid Review ID, nothing will happen.</p>

        <form method="POST" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Review ID: <input type="text" name="reviewid"> <br /><br />
            Game ID: <input type="text" name="gameid"> <br /><br />

            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />


        <h2>Count the number of reviews in a game</h2>
        <p>If you enter an invalid Review ID, nothing will happen.</p>

        <form method="GET" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            Game ID: <input type="text" name="gameid"> <br /><br />

            <input type="submit" value="Count" name="countTuples"></p>
        </form>

        <h2>Display the reviews for a game</h2>
        <p>If you enter an invalid Review ID, nothing will happen.</p>

        <form method="GET" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayReviewsRequest" name="displayReviewsRequest">
            Game ID: <input type="text" name="gameid"> <br /><br />
            <input type="submit" value="Display" name="displayReviews"></p>
        </form>
        
        <h2>Display all reviews in ReviewTable</h2>
        <form method="GET" action="review.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display" name="displayTuples"></p>
        </form>

        <h2>Go to the Game Page</h2>
        <form method="GET" action="game.php"> <!--refresh page when submitted-->
            <input type="submit" value="Go"></p>
        </form>

        <h2>Watch the Game's Review on YouTube!</h2>
        <form method="GET" action="https://youtu.be/dQw4w9WgXcQ"> <!--refresh page when submitted-->
            Game ID: <input type="text" name="gameid"> <br /><br />
            <input type="submit" value="Go"></p>
        </form>

        <hr />
        <hr />

        <?php

        $success = True; 
        $db_conn = NULL; 
        $show_debug_alert_messages = False; 
        
        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
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

        function printResult($result) { 
            define("c", "<center>");
            define("d", "</center>");
            define("td", "</td><td>");
            
            echo "<br>Retrieved data from the review table:<br>";
            echo "<table>";
            echo "<tr><th><center>Review ID</center></th><th><center>Game ID</center></th><th><center>User ID</center></th><th><center>Review</center></th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . c . $row["REVIEWID"] . d . td . c . $row["GAMEID"] . d . td . c . $row["USERID"] . d . td. c . $row["TEXT"] . d . "</td></tr>";
            }

            echo "</table>";
        }

        function connectToDB() {
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

        function disconnectFromDB() {
            global $db_conn;

            debugAlertMessage("Disconnect from Database");
            OCILogoff($db_conn);
        }

        // function handleResetRequest() {
        //     global $db_conn;
        //     // Drop old table
        //     executePlainSQL("DROP TABLE review");

        //     // Create new table
        //     echo "<br> Resetting Reviews <br>";
        //     executePlainSQL("CREATE TABLE review
        //     (reviewID int NOT NULL,
        //     gameID int NOT NULL,
        //     userID int NOT NULL,
        //     text varchar(1000),
        //     PRIMARY KEY (reviewID, gameID),
        //     FOREIGN KEY (gameID) REFERENCES game ON DELETE CASCADE,
        //     FOREIGN KEY (userID) REFERENCES users ON DELETE CASCADE)");
            
        //     OCICommit($db_conn);
        // }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insReview'],
                ":bind2" => $_POST['insNo'],
                ":bind3" => $_POST['insUser'],
                ":bind4" => $_POST['insText'],
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into review values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
            OCICommit($db_conn);
        }

        function handleUpdateRequest() {
            global $db_conn;

            $review_id = $_POST['reviewid'];
            $game_id = $_POST['gameid'];
            $new_review = $_POST['newReview'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE review SET text='" . $new_review . "' WHERE reviewID='" . $review_id . "' AND gameID='" . $game_id . "'");
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
            global $db_conn;

            $game_id = $_POST['gameid'];
            $review_id = $_POST['reviewid'];

            executePlainSQL("DELETE FROM review WHERE reviewID='" . $review_id . " 'AND gameID='" . $game_id . "'");
            OCICommit($db_conn);

        }

        

        function handleCountRequest() {
            global $db_conn;
            
            $game_id = $_GET['gameid'];

            $result = executePlainSQL("SELECT Count(g.gameID) FROM game g, review r WHERE g.gameID = r.gameID AND g.gameID='" . $game_id . "'");
            // $result = executePlainSQL("SELECT Count(*) FROM reviewTable WHERE gameid='" . $game_id . "'");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of reviews in this game: " . $row[0] . "<br>";
            }
            
        }


        function handleDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM review ORDER BY gameID, reviewID");

            printResult($result);
        }

        function handleDisplayReviewsRequest() {
            global $db_conn;

            $game_id = $_GET['gameid'];

            $result = executePlainSQL("SELECT * FROM review WHERE gameID='" . $game_id . "'" . "ORDER BY gameID, reviewID");
            

            printResult($result);
            
        }

        // HANDLE ALL POST ROUTES
        function handlePOSTRequest() {
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
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if (array_key_exists('displayTuples', $_GET)){
                    handleDisplayRequest();
                } else if (array_key_exists('displayReviews', $_GET)){
                    handleDisplayReviewsRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        } else if (isset($_GET['displayTupleRequest'])){
            handleGETRequest();
        } else if (isset($_GET['displayReviewsRequest'])){
            handleGETRequest();
        }
		?>
	</body>
</html>

