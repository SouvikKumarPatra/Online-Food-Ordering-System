<?php
	//Include constant page
	include('../config/constant.php');

	//echo "Delete food page";
	
	if(isset($_GET['id']) && isset($_GET['image_name']))
	{
		//Process to delete
		//echo "Process to Delete";

		//1.Get id and image name
		$id = $_GET['id'];
		$image_name = $_GET['image_name'];

		//2.Remove the image if avaiable
		//Chcek whether the imagename avilable or not
		if($image_name != "")
		{
			//it has image and need to remove from folder
			//Get th eimage path
			$path = "../images/food/".$image_name;

			//Remove image title from the folder
			$remove = unlink($path);

			//Check whether the image is remove or not
			if($remove==false)
			{
				//Failed to remove image
				$_SESSION['upload'] = "<div class='error>Failed to Remove Image.</div>";
				header('location:'.SITEURL.'admin/manage-food.php');
				//Stop the process of deleting food 
				die();
			}
		}
		//3.Delete food from database 
		$sql = "DELETE FROM tbl_food WHERE id=$id";
		//Execute the query
		$res = mysqli_query($conn, $sql); 

		//Check whether the query is executed or not and set the session message respectively
		//4.Redirect to manage food  with session message
		if($res==true)
		{
			//Food deleted
			$_SESSION['delete'] = "<div class='success'>Food Deleted Successfully.</div>";
			header('location:'.SITEURL.'admin/manage-food.php');
		}
		else
		{
			//Faied to delete food
			$_SESSION['delete'] = "<div class-'error'>Failed to Delete.</div>";
			header('location:'.SITEURL.'admin/manage-food.php');
		}

	}
	else
	{
		//Redirect to manage food page
		//echo "Redirect";
		$_SESSION['unauthorize'] = "<div class='error>Unathorized Access.</div>";
		header('location:'.SITEURL.'admin/manage-food.php');
	}

?>