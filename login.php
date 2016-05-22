<?php
session_start();

// Init
include "settings.php";
include PATH . "/class/blog.class.php";
$Blog = new Blog($linkID);
// End Init


$sql = "SELECT * FROM `users` WHERE `username` = '$_GET[username]' AND `password` = '$_GET[password]'";
$result = $Blog->new_mysql($sql);
while ($row = $result->fetch_assoc()) {
        foreach ($row as $key=>$value) {
                $_SESSION[$key] = $value;
        }
	$_SESSION["isLoggedIn"] = true;
        $found = "1";
}


if ($found == "1") {
	print URL ."index.php?section=admin&part=dashboard";
} else {
        $Blog->login('<font color=red>The username and or password was incorrect.</font>');
}

?>
