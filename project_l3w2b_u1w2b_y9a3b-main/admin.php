<?php
    include_once 'header.php';
?>
<section class="admin-login">
    <form method="POST" action="includes/dbh.inc.php">
        <input type="hidden" id="loginAdminRequest" name="loginAdminRequest">
        <input type="text" name="adminUsername" placeholder="username">
        <input type="password" name="adminPassword" placeholder="password">
        <input type="submit" value="login" name="adminLoginSubmit"></p>
         <?php
        if (isset($_GET["error"])) {
            if ($_GET["error"] == "emptyInput") {
                echo "<p> Please fill in empty fields </p>";
            } else if ($_GET["error"] == "wrongLogin") {
                echo "<p> Wrong Login attempt </p>";
            } else if ($_GET["error"] == "failedLogin") {
                echo "<p> Wrong Password </p>";
            } 
        }
        if (isset($_GET["adminID"])) {
                echo "<p>&#10004;</p>";
                echo "Login Successful, your adminID is: <b>" . $_GET["adminID"] . "</b>";
        }
    ?>
    </form>
   
</section>

<section class="admin-page">
 
    <h2>Reset Tables</h2>
    <p>Warning! All tables will be reset if you press Reset!</p>

    <form method="POST" action="includes/dbh.inc.php">
        <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
        <p><input type="submit" value="Reset" name="reset"></p>
    </form>

    <hr />

    <h2>Add New Game</h2>
    <form method="POST" action="includes/dbh.inc.php"> <!--refresh page when submitted-->
        <input type="hidden" id="insertGameRequest" name="insertGameRequest">
        Name: <input type="text" name="insName"> 
        Genre: <input type="text" name="insGenre"> 
        Score (0-5): <input type="number" name="insScore" min="0" max="5"> 
        Studio: <input type="text" name="insStudio"> 
        AdminID: <input type="text" name="insAdminID"> 
        <input type="submit" value="Add" name="insertSubmit"></p>
    </form>
    <hr />
    

    <h2>Update Game Name</h2>
   
    <form method="POST" action="includes/dbh.inc.php"> <!--refresh page when submitted-->
        <input type="hidden" id="updateGameNameRequest" name="updateGameNameRequest">
        Game ID: <input type="text" name="gameID">
        New Name: <input type="text" name="newName"> 

        <input type="submit" value="Update" name="updateSubmit"></p>
    </form>
    <hr />
    

    <h2>Delete Game</h2>
    <p>If you enter an invalid Game ID, nothing will happen.</p>

    <form method="POST" action="includes/dbh.inc.php"> <!--refresh page when submitted-->
        <input type="hidden" id="deleteGameRequest" name="deleteGameRequest">
        Game ID: <input type="text" name="gameID"> 

        <input type="submit" value="Delete" name="deleteGameSubmit"></p>
    </form>
    <hr />
</section>
<?php
    include_once 'footer.php';
?>

