<?php

if(!isset($_SESSION["user_type"]) || !$_SESSION["user_type"]=="Customer" || !isset($_SESSION["user_id"])){
	header("Location:login.php"); 
}