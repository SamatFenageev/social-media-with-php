<?php
    require_once 'HEADER.php';
    if (isset($_SESSION['user'])) {
        destroySession();
        echo "<br><div class='center'> You have been logged out. Please <a data-transition='slide' href='index.php'>Click Here</a> to refresh the screen.</div>";
    }
    else echo "<div class='center'>You cannot log out because you are not logged in</div>";
?>

</div>
</body>
</html>