<?php
    require_once 'HEADER.php';

    if(!$loggedin) die("</div></body></html>");
    if(isset($_GET['view'])) $view = sanitizeString($_GET['view']);
    else $view = $user;

    if ($view == $user) {
        $name1 = $name2 = "Your";
        $name3 = "You are";
    }
    else{
        $name1 = "<a data-transition='slide' href='MEMBERS.php?view=$view'></a>'s";
        $name2 = "$view's";
        $name3 = "$view is";
    }
    showProfile($view);

    $followers = array();
    $following = array();

    $result = queryMysql("SELECT * FROM friends WHERE user='$view'");
    $num = $result->num_rows;

    for ($j=0; $j < $num; $j++) { 
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $followers[$j] = $row['friend'];
    }

    $result = queryMysql("SELECT * FROM friends WHERE friend='$view");
    $num = $result->num_rows;
    for ($j=0; $j < $num; $j++) { 
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $following[$j] = $row['user'];
    }
    $mutual = array_inspect($followers, $following);
    
    $followers = array_diff($followers, $mutual);
    $following = array_diff($following, $mutual);
    $friends = FALSE;
    echo "<br>";

    if (sizeof($mutual)) {
        echo "<span class='subhead'>$name2 followers</span><ul>";
        foreach ($mutual as $friend) echo "<li><a data-transition='slide' href='MEMBERS.php?$friend'>$friend</a>";
        echo "</ul>";
        $friends = TRUE;
    }

    if (sizeof($followers)) {
        echo "<span class='subhead'>$name2 mutual Friends</span><ul>";
        foreach ($followers as $friend) echo "<li><a data-transition='slide' href='MEMBERS.php?$friend'>$friend</a>";
        echo "</ul>";
        $friends = TRUE;
    }

    if (sizeof($following)) {
        echo "<span class='subhead'>$name3 mutual Friends</span><ul>";
        foreach ($following as $friend) echo "<li><a data-transition='slide' href='MEMBERS.php?$friend'>$friend</a>";
        echo "</ul>";
        $friends = TRUE;
    }

    if (!$friends) echo "<br>You don't have any friends yet.<br><br>";
    echo "<a data-role='button' data-transition='slide' href='MESSAGES.php?view=$view'>View $name2 messages</a>";
    
?>
</div>
</body>
</html>