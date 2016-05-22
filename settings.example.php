<?php

$HOST = "localhost";
$USER = "database username";
$PASS = "database password";
$DB = "database name";

define('HOST',$HOST);
define('USER',$USER);
define('PASS',$PASS);
define('DB',$DB);
define('PATH','/home/yourusername/www/blog');
define('URL','http://www.yourname.com/blog/');

$linkID = new mysqli(HOST,USER,PASS,DB);
if (mysqli_connect_errno()) {
   printf("Connect failed: %s\n", mysqli_connect_error());
   exit();
}
?>
