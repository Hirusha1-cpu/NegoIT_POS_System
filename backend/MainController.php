<?php
session_start();
include_once 'template/common.php';

if ((isset($_COOKIE['userkey'])) && (isset($_SESSION['userkey']))) {
	if ($_SESSION['userkey'] == $_COOKIE['userkey']) {
		if (isset($_REQUEST['components'])) {
			switch ($_REQUEST['components']) {
				case "backend":
					include_once 'components/backend/backend.php';
					break;
				case "authenticate":
					include_once 'components/authenticate/authenticate.php';
					break;
				default:
					header('Location: index.php?components=backend&action=lock');
					break;
			}
		} else {
			header('Location: index.php?components=backend&action=lock');
		}
	} else {
		if (isset($_REQUEST['components'])) {
			switch ($_REQUEST['components']) {
				case "authenticate":
					include_once 'components/authenticate/authenticate.php';
					break;
				default:
					header('Location: index.php?components=authenticate&action=logout');
					break;
			}
		} else {
			header('Location: index.php?components=authenticate&action=logout');
		}
	}
}
if ((!isset($_SESSION['userkey'])) || (!isset($_COOKIE['userkey']))) {
	if (isset($_REQUEST['components'])) {
		switch ($_REQUEST['components']) {
			case "authenticate":
				include_once 'components/authenticate/authenticate.php';
				break;
			default:
				header('Location: index.php?components=authenticate&action=logout');
				break;
		}
	} else {
		header('Location: index.php?components=authenticate&action=logout');
	}
}
?>