<?php
session_start();

if(!isset($_SESSION["user_type"]) || (!$_SESSION["user_type"]=="Admin"|| !$_SESSION["user_type"]=="Staff") || !isset($_SESSION["user_id"])){
	header("Location:../login.php"); 
}
require_once('../config.php');
require_once('../thumb_config.php');

$phpThumbBase  = '../lib/phpThumb/phpThumb.php';

?>