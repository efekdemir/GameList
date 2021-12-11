<?php
    include_once 'header.php';
?>
        <h1>Welcome to the Developers Page!</h1>

        <!-- <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>
        <form method="POST" action="developer.php">
            if you want another page to load after the button is clicked, you have to specify that page in the action parameter
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>
                                                                            Not needed anymore
        <hr /> -->

        <h2>Add a New Developer to the Site</h2>
        <form method="POST" action="developer.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Developer ID: <input type="text" name="insID"> <br /><br />
            Developer Name: <input type="text" name="insName"> <br /> <br />
            Bio: <input type="text" name="insBio"> <br /> <br />

            <input type="submit" value="Add" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Developer Name in Table</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="developer.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Old Name: <input type="text" name="oldName"> <br /><br />
            New Name: <input type="text" name="newName"> <br /><br />

            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Delete Developer</h2>
        <p>If you enter an invalid Developer ID, nothing will happen.</p>
        <p>Please keep in mind the text box is case and space sensitive.</p>

    <form method="POST" action="developer.php">
        <!--refresh page when submitted-->
        <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
        Developer ID: <input type="text" name="devID"> <br /><br />

        <input type="submit" value="Delete" name="deleteSubmit"></p>
    </form>

    <hr />

        <h2>Count the Number of Developers Added So Far</h2>
        <form method="GET" action="developer.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" value="Count" name="countTuples"></p>
        </form>

        <hr />

        <h2>Display the Developers in the Database</h2>

        <form method="GET" action="developer.php"> <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
            <input type="submit" value="Display" name="displayTuples"></p>
        </form>

        <h2>Find Developers Who Worked in ALL of the Studios Available in the Database</h2>
        <p>These are beasts that are just built different and not only develop games for a career
                 but THEY LIVE IT!!</p>

        <form method="GET" action="developer.php"> <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="findTupleRequest" name="findTupleRequest">
            <input type="submit" value="Find Them!" name="findTuples"></p>
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
            echo "<tr><th> Developer ID </th><th> Developer Name </th><th> Bio </th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {

                echo "<tr><td>" . $row["DEVID"] . "</td><td align=center>" . $row["DEVNAME"] . 
                "</td><td align=center>" . $row["BIO"] . "</td></tr>";
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

            $old_name = $_POST['oldName'];
            $new_name = $_POST['newName'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE developer SET devName='" . $new_name . "' WHERE devName='" . $old_name . "'");
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
        global $db_conn;

        $devID = $_POST['devID'];

        executePlainSQL("DELETE FROM developer WHERE devID = '$devID'");
        OCICommit($db_conn);
    }

        // function handleResetRequest() {
        //     global $db_conn;
        //     // Drop old table
        //     executePlainSQL("DROP TABLE developer");    No need for anymore due to db.sql script

        //     // Create new table
        //     echo "<br> creating new table <br>";
        //     executePlainSQL("CREATE TABLE developer (devID varchar(30) PRIMARY KEY, image char(30))");
        //     OCICommit($db_conn);
        // }

        function handleInsertRequest() {
            global $db_conn;

            //Getting the values from user and insert data into the table
            $tuple = array (
                ":bind1" => $_POST['insID'],
                ":bind2" => $_POST['insName'],
                ":bind3" => $_POST['insBio']
            );

            $alltuples = array (
                $tuple
            );

            executeBoundSQL("insert into developer values (:bind1, :bind2, :bind3)", $alltuples);
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM developer");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in developer: " . $row[0] . "<br>";
            }
        }

        function handleDisplayRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM developer");

            printResult($result);
        }

        function handleFindRequest() {
            global $db_conn;

            $result = executePlainSQL("
            SELECT *
            FROM developer d
            WHERE NOT EXISTS
            ((SELECT g.studioName
            FROM gameStudio g) 
            MINUS
            (SELECT ww.studioName
            FROM workedWith ww
            WHERE ww.devID = d.devID))");
            
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
                } else if (array_key_exists('findTuples', $_GET)) {
                    handleFindRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['insertSubmit'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest']) || isset($_GET['findTupleRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
    </html>