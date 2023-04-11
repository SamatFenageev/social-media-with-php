<?php
    require_once 'HEADER.php';

    if(!$loggedin) die("</div></body></html>");

    if(isset($_GET['view'])) $view = sanitizeString($_GET['view']);
    else $view = $user;

    if (isset($_POST['text'])) {
        $text = sanitizeString($_POST['text']);

        if ($text != "") {
            $pm = substr(sanitizeString($_POST['pm']), 0, 1);
            $time = time();
            queryMysql("INSERT INTO messages VALUES(NULL,'$user', '$view', '$pm', $time, '$text'");
        }
    }

    if ($view != "") {
        if ($view == $user) $name1=$name2="Your";
        else{
            $name1 = "<a href='MEMBERS.php?view=$view'>$view</a>'s"
            $name2 = "$view's";
        }

        echo "<h3>$name1 Messages</h3>";
        showProfile($view);

        echo <<<_END
        <form method='post' action='MESSAGES.php?view=$view'>
        <fieldset data-role="controlgroup" data-type="horizontal">
        <ledend>Type here to leave a message</legend>
        <input type='radio' name='pm' id='public' value='0' checked='checked'>
        <label for='public'>Public</label>
        <input type='radio' name='pm' id='private' value='1'>
        <label for='private'>Private</label>
        </fieldset>
        <textarea name='text'></textarea>
        <input data-transition='silde' type='submit' value='Post Message'>
        </form><br>
        _END;
        date_default_timezone_set('UTC');
        if (isset(_$GET['erase'])) {
            $erase = sanitizeString($_GET['erase']);
            queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$user'");
        }

        $query = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
        $result = queryMysql($query);
        $num = $result->num_rows;

        for ($i=0; $i < $num; $i++) { 
            $row = $result->fetch_array(MYSQLI_ASSOC);

            if ($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user) {
                echo date('M jS \'y g:ia:', $row['time']);
                echo "<a href='messages.php?view=".$row['auth'] . "'>" . $row['auth']."</a>";
                if($row['pm'] == 0) echo "wrote: &quot;".$row['message'] . "&quot;";
                else echo "whispered:<span class='whisper'>&quot;" . $row['message']."&quot;</span>";

                if($row['recip'] == $user) echo "[<a href='MESSAGES.php?view=$view"."&erase=".$row['id'] ."'>erase</a>";
                echo "<br>";
            }
        }
    }
    if(!$num) echo "<br><span class='info'>No messages yet</span><br><br>";

    echo "<br><a data-role='button' href='MESSAGES.php?view=$view'>Refresh messages</a>";
?>
</div>
</body>
</html>