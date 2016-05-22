<?php
session_start();

// Init
include "settings.php";
include PATH . "/class/blog.class.php";
$Blog = new Blog($linkID);
// End Init

switch($_GET['part']) {
	case "dashboard":
	$title = " : Admin Dashboard";
	break;

	case "new":
	$title = " : New Blog";
	break;

	case "list":
	$title = " : List Blog";
	break;

	case "profile":
	$title = " : Profile";
	break;

	case "edit":
	$title = " : Edit Blog";
	break;
}

include "templates/header.php";

// routes
if ($_GET['section'] != "") {
	$section = $_GET['section'];
}
if ($_POST['section'] != "") {
	$section = $_POST['section'];
}


if ($section == "") {
	$Blog->show_blog();
}

if ($section == "admin") {
	$logged = $Blog->check_login();
	if ($logged == "TRUE") {

		if ($_GET['part'] != "") {
			$part = $_GET['part'];
		}
		if ($_POST['part'] != "") {
			$part = $_POST['part'];
		}
		switch ($part) {

			case "dashboard":
			$Blog->admin_menu();
			break;

			case "new":
			$Blog->admin_new();
			break;

			case "logout":
			$Blog->logout();
			break;

			case "save":
			$Blog->saveblog();
			break;

			case "list":
			$Blog->listblog();
			break;

			case "edit":
			$Blog->editblog();
			break;

			case "update":
			$Blog->updateblog();
			break;
			case "delete":
			$Blog->deleteblog();
			break;

			case "profile":
			$Blog->profile();
			break;

			case "updateprofile":
			$Blog->updateprofile();
			break;

			default:
			$Blog->admin_menu();
			break;

		}

	} else {
		$Blog->login($null);
	}	
}


// end routes



include "templates/footer.php";
?>
