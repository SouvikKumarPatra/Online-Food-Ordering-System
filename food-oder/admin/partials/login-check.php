<?php

	// Authotization - Acess Control
	//Check whether the user logged im or not
	if(!isset($_SESSION['user'])) //If user session is not set
	{
		//User is not logged in
		//Redirect to login page with message
		$_SESSION['no-login-message'] = "<div class='error text-center'>Please login to access Admin Panel.</div>";
		//Redirect to login page
		header('location:'.SITEURL.'admin/login.php');
	}


?>