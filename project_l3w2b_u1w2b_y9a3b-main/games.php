<?php
    include_once 'header.php';
?>
    <section class="game-list">
         <h2>Display Top Rated Games</h2>
        <form method="GET" action="includes/dbh.inc.php">
            <input type="hidden" id="displayTopGamesRequest" name="displayTopGamesRequest">
            <input type="submit" value="Display" name="displayTopGames"></p>
        </form>

        <h2>Count the number of games in GameTable</h2>
        <form method="GET" action="game.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" value="Count" name="countTuples"></p>
        </form>

        <h2>Find the highest rated game in GameTable</h2>
        <form method="GET" action="game.php"> <!--refresh page when submitted-->
            <input type="hidden" id="maxTupleRequest" name="maxTupleRequest">
            <input type="submit" value="Find" name="maxTuples"></p>
        </form>

        <h2>Find the lowest rated game in GameTable</h2>
        <form method="GET" action="game.php"> <!--refresh page when submitted-->
            <input type="hidden" id="minTupleRequest" name="minTupleRequest">
            <input type="submit" value="Find" name="minTuples"></p>
        </form>

        <h2>Filtered Score Display</h2>
        <p>Let's you see the games with average scores higher than the input.</p>
        <form method="GET" action="game.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayFilterRequest" name="displayFilterRequest">
            Score: <input type="text" name="score"> <br /><br />
            <input type="submit" value="Display" name="displayFilter"></p>
        </form>

        <h2>Filtered Game Studio Display</h2>
        <p>Let's you see the average score of the games made by Game Studios that have more than X games.</p>
        <form method="GET" action="game.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayStudioRequest" name="displayStudioRequest">
            Number of Games: <input type="text" name="number"> <br /><br />
            <input type="submit" value="Display" name="displayStudio"></p>
        </form>
    </section>
<?php
    include_once 'footer.php';
?>