<?php
	$dbhost = 'localhost';
	$dbname = 'robinsnest';
	$dbuser = 'robinsnest';
	$dbpass = 'rnpassword';

	$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if ($connection->connect_error) die ('Fatal Error');

	function createTable($name, $query)
	{
		queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
		echo "Table $name is created or was already created<br>";
	}

	function queryMysql($query)
	{
		global $connection;
		$result = $connection->query($query);
		if (!$result) die ("Fatal error");
		return $result;
	}

	function destroySession()
	{
		$_SESSION = array();
		if(session_id() != "" || isset($_COOKIE[session_name()])) setcookie(session_name(), '', time()-2592000, '/');
		session_destroy();
	}

	function sanitizeString($var)
	{
		global $connection;
		$var = strip_tags($var);
		$var = htmlentities($var);
		if (get_magic_quotes_gpc()) $var = stripcslashes($var);
		return $connection->real_escape_string($var);
	}

	function showProfile($user)
	{
		if (file_exists("$user.jpg"))
      echo "<img src='$user.jpg' style='left;'>";

		$result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
		if ($result->num_rows) {
				$row = $result->fetch_array(MYSQL_ASSOC);
				echo stripcslashes($row['text']) . "<br style='clear:left;'><br>";
		}
		else echo "<p>Here is nothing to look at yet</p><br>";
	}
?>