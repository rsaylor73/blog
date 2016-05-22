<?php           

if( !class_exists( 'Blog')) {
class Blog { 
        public $linkID;
                
        function __construct($linkID){ $this->linkID = $linkID; }
                
        /*      
        The function is set to only allow mysql calls to be driven
        from inside this class.
        */      
                
        public function new_mysql($sql) {
                $result = $this->linkID->query($sql) or die($this->linkID->error.__LINE__);
                return $result;
        }       

                
        // check login system
        public function check_login() {
                $sql = "SELECT `id` FROM `users` WHERE `username` = '$_SESSION[username]' AND BINARY `password` = '$_SESSION[password]'";
                $result = $this->new_mysql($sql);
                while ($row = $result->fetch_assoc()) {
                        $found = "1";
                }
                if ($found == "1") {
                        return "TRUE";
                } else {
                        return "FALSE";
                }
        }       


        public function login($msg) {

                if ($msg != "") {
                        print "<center><font color=red>$msg</font></center><br>";
                }

                print "
                <br>
                <form name=\"myform\" id=\"myform\">
		<div id=\"login-scr\">
                <table border=0 width=700>
                <tr><td>
                        <table class=\"table\">
                                <tr><td>Username:</td><td><input type=\"text\" name=\"username\" size=20></td></tr>
                                <tr><td>Password:</td><td><input type=\"password\" name=\"password\" onkeypress=\"if(event.keyCode==13) { login(this.form); return false;}\" size=20></td></tr>
                                <tr><td>&nbsp;</td><td><input type=\"button\" class=\"btn btn-primary\" value=\"Login\" onclick=\"login(this.form)\"></td></tr>
                        </table>
                </td></tr>
                </table>
                </form>
		</div>
                <br>";
                
                ?>
                                <script>
                                function login(myform) {
                                        $.get('login.php',
                                        $(myform).serialize(),
                                        function(php_msg) {
                                          if (php_msg.substring(0,4) == "http") {
                                             $("#login-scr").html('<span class="details-description"><font color=green>Login successful. Loading please wait...</font><br></span>');
                                             setTimeout(function()
                                                {
                                                window.location.replace(php_msg)
                                                }
                                             ,2000);
                                          } else {
                                             $("#login-scr").html(php_msg);
                                          }
                                        });
                                }
                                </script>
                <?php
                
        }

	public function admin_menu() {

		if (temp != "temp") {
			print temp;
		}
		define('temp','<br>');
		print "<ul>
			<li><a href=\"index.php\"><i class=\"fa fa-home\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Home</a></li>
			<li><a href=\"index.php?section=admin&part=new\"><i class=\"fa fa-plus\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Add New Blog</a></li>
			<li><a href=\"index.php?section=admin&part=list\"><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;View / Edit Blog</a></li>
			<li><a href=\"index.php?section=admin&part=profile\"><i class=\"fa fa-user\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Profile</a></li>
			<li><a href=\"index.php?section=admin&part=logout\"><i class=\"fa fa-sign-out\" aria-hidden=\"true\"></i>&nbsp;&nbsp;&nbsp;Logout</a></li>
		</ul>";
	}

	public function admin_new() {
		print "<form action=\"index.php\" method=\"post\">
		<input type=\"text\" name=\"title\" placeholder=\"Blog Title\" required size=\"40\"><br><br>
		<input type=\"hidden\" name=\"section\" value=\"admin\">
		<input type=\"hidden\" name=\"part\" value=\"save\">
		<textarea name=\"blog\" id=\"tiny\"></textarea><br>
		<input type=\"submit\" value=\"Save\" class=\"btn btn-primary\">&nbsp;&nbsp;<input type=\"button\" value=\"Cancel\" onclick=\"document.location.href='index.php?section=admin&part=dashboard'\" class=\"btn btn-warning\"><br>
		</form>";
	}

	public function logout() {
		session_destroy();
		print "<br><font color=green>You have been logged out.<br><br>
		<input type=\"button\" value=\"Log back in\" class=\"btn btn-primary\" onclick=\"document.location.href='index.php?section=admin'\">
		</font><br>";
	}

	public function saveblog() {
		$today = date("Ymd");
		$sql = "INSERT INTO `blog` (`userID`,`blog`,`date_added`,`date_updated`,`title`) VALUES ('$_SESSION[id]','$_POST[blog]','$today','$today','$_POST[title]')";
		$result = $this->new_mysql($sql);
		if ($result == "TRUE") {
			define('temp','<font color=green>The blog was added.<br></font>');
		} else {
			define('temp','<font color=red>The blog failed to add.<br></font>');
		}
		$this->admin_menu();
	}


	public function listblog() {
		$sql = "SELECT * FROM `blog` ORDER BY `title` ASC";
		$result = $this->new_mysql($sql);
		print "<table class=\"table\">";
		while ($row = $result->fetch_assoc()) {
			print "<tr><td width=\"80%\">$row[title]</td><td>
			<a href=\"index.php?section=admin&part=edit&id=$row[id]\" alt=\"Edit\" title=\"Edit\"><i class=\"fa fa-pencil-square-o\" aria-hidden=\"true\"></i></a>&nbsp;&nbsp;
			<a href=\"index.php?section=admin&part=delete&id=$row[id]\" onclick=\"return confirm('You are about to delete $row[title]. Click OK to continue.')\"><i class=\"fa fa-trash\" aria-hidden=\"true\"></i></a>

			</td></tr>";
		}
		print "</table>";
	}

	public function editblog() {
		$sql = "SELECT * FROM `blog` WHERE `id` = '$_GET[id]'";
		$result = $this->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
	                print "<form action=\"index.php\" method=\"post\">
        	        <input type=\"text\" name=\"title\" placeholder=\"Blog Title\" required size=\"40\" value=\"$row[title]\"><br><br>
                	<input type=\"hidden\" name=\"section\" value=\"admin\">
	                <input type=\"hidden\" name=\"part\" value=\"update\">
			<input type=\"hidden\" name=\"id\" value=\"$row[id]\">
        	        <textarea name=\"blog\" id=\"tiny\">$row[blog]</textarea><br>
                	<input type=\"submit\" value=\"Update\" class=\"btn btn-primary\">&nbsp;&nbsp;<input type=\"button\" value=\"Cancel\" onclick=\"document.location.href='index.php?section=admin&part=dashboard'\" 
			class=\"btn btn-warning\"><br>
	                </form>";

		}
	}

	public function updateblog() {
		$today = date("Ymd");
		$sql = "UPDATE `blog` SET `title` = '$_POST[title]', `blog` = '$_POST[blog]', `date_updated` = '$today'";
                $result = $this->new_mysql($sql);
                if ($result == "TRUE") {
                        define('temp','<font color=green>The blog was updated.<br></font>');
                } else {
                        define('temp','<font color=red>The blog failed to update.<br></font>');
                }
                $this->admin_menu();

	}

	public function deleteblog() {
		$sql = "DELETE FROM `blog` WHERE `id` = '$_GET[id]'";
                $result = $this->new_mysql($sql);
                if ($result == "TRUE") {
                        define('temp','<font color=green>The blog was deleted.<br></font>');
                } else {
                        define('temp','<font color=red>The blog failed to delete.<br></font>');
                }
                $this->admin_menu();

	}

	public function profile() {
		print "<form action=\"index.php\" method=\"post\">
		<input type=\"hidden\" name=\"section\" value=\"admin\">
		<input type=\"hidden\" name=\"part\" value=\"updateprofile\">
		<table class=\"table\">
		<tr><td>Username:</td><td>$_SESSION[username]</td></tr>
		<tr><td>Password:</td><td><input type=\"password\" name=\"newpassword\" size=\"40\" required></td></tr>
		<tr><td>&nbsp;</td><td><input type=\"submit\" value=\"Update\" class=\"btn btn-primary\"></td></tr>
		</table>
		</form>";
	}

	public function updateprofile() {
		$sql = "UPDATE `users` SET `password` = '$_POST[newpassword]' WHERE `id` = '$_SESSION[id]'";
                $result = $this->new_mysql($sql);
                if ($result == "TRUE") {
                        define('temp','<font color=green>Your password was updated.<br></font>');
                } else {
                        define('temp','<font color=red>Your password failed to update.<br></font>');
                }
                $this->admin_menu();

	}


	public function show_blog() {
		$sql = "SELECT * FROM `blog`";
		$result = $this->new_mysql($sql);
		while ($row = $result->fetch_assoc()) {
			print "$row[blog]<br><br>";
		}
	}

// end class
}
}
?>
