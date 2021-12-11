<?php
    include_once 'header.php';
?>
        <h1>Welcome to the Game Studios Page!</h1>

        <!-- <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>
        <form method="POST" action="gameStudio.php">
            if you want another page to load after the button is clicked, you have to specify that page in the action parameter
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>
                                                                            Not needed anymore
        <hr /> -->

        <h2>Add a New Game Studio to the Site</h2>
        <form method="POST" action="gameStudio.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Studio Name: <input type="text" name="insName"> <br /><br />
            Image Path: <input type="text" name="insPath"> <br /> <br />

            <input type="submit" value="Add" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Studio Logo File Name in Table</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="gameStudio.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Path: <input type="text" name="oldPath"> <br /><br />
            New Path: <input type="text" name="newPath"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Delete Game Studio</h2>
        <p>If you enter an invalid Studio Name, nothing will happen.</p>
        <p>Please keep in mind the text box is case and space sensitive.</p>

    <form method="POST" action="gameStudio.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
        Studio Name: <input type="text" name="studioName"> <br /><br />

        <input type="submit" value="Delete" name="deleteSubmit"></p>
    </form>

    <hr />

        <h2>Count the Number of Studios Added So Far</h2>
        <form method="GET" action="gameStudio.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" value="Count" name="countTuples"></p>
        </form>

        <hr />

        <h2>Display the Studios in the Database</h2>

        <form method="GET" action="gameStudio.php"> <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display" name="displayTuples"></p>
        </form>


        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

        function debugAlertMessage($message) {
            global $show_debug_alert_messages;

            if ($show_debug_alert_messages) {
                echo "<script type='text/javascript'>alert('" . $message . "');</script>";
            }
        }

        function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
            //echo "<br>running ".$cmdstr."<br>";
            global $db_conn, $success;

            $statement = OCIParse($db_conn, $cmdstr); 
            //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

            if (!$statement) {
                echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
                echo htmlentities($e['message']);
                $success = False;
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
                $success = False;
            }

			return $statement;
		}

        function executeBoundSQL($cmdstr, $list) {
            /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection. 
		See the sample code below for how this function is used */

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
                    //echo $val;
                    //echo "<br>".$bind."<br>";
                    OCIBindByName($statement, $bind, $val);
                    unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
				}

                $r = OCIExecute($statement, OCI_DEFAULT);
                if (!$r) {
                    echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                    $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                    echo htmlentities($e['message']);
                    echo "<br>";
                    $success = False;
                }
            }
        }

        function printResult($result) { //prints results from a select statement
            echo "<br>Retrieved data from the database:<br>";
            echo "<table>";
            echo "<tr><th> Studio Name</th><th>Studio Logo</th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                $img = $row["IMAGE"];

                echo "<tr><td>" . $row["STUDIONAME"] . "</td><td align=center>" . "<img src=studioImages/$img>" . "</td></tr>";
            }
                                                //make sure to set the patch correctly
            echo "</table>";
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

        function handleUpdateRequest() {
            global $db_conn;

            $old_path = $_POST['oldPath'];
            $new_path = $_POST['newPath'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE gameStudio SET image='" . $new_path . "' WHERE image='" . $old_path . "'");
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
        global $db_conn;

        $studioName = $_POST['studioName'];

        executePlainSQL("DELETE FROM gameStudio WHERE studioName = '$studioName'");
        OCICommit($db_conn);
    }

        // function handleResetRequest() {
        //     global $db_conn;
        //     // Drop old table
        //     executePlainSQL("DROP TABLE gameStudio");    No need for anymore due to db.sql script

        //     // Create new table
        //     echo "<br> creating new table <br>";
        //     executePlainSQL("CREATE TABLE gameStudio (studioname varchar(30) PRIMARY KEY, image char(30))");
        //     OCICommit($db_conn);
        // }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insName'],
                ":bind2" => $_POST['insPath']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into gameStudio values (:bind1, :bind2)", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM gameStudio");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in gameStudio: " . $row[0] . "<br>";
            }
        }

        function handleDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM gameStudio");

            printResult($result);
        }

        // HANDLE ALL POST ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
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
                } else if (array_key_exists('displayTuples', $_GET)) {
                    handleDisplayRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
    </html>