<?php

 include('../config/constant.php');

//1.Get id of the admin to be deleted
$id = $_GET['id'];

//2.Create sql query to delete admin
$sql = "DELETE FROM tbl_admin WHERE id=$id";

//Execute the query
$res = mysqli_query($conn, $sql);

if($res==true)
{
	//echo "Admin Deleted";
	//create session variable to display message
	$_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully.</div>";
	//Redirect to manage admin page
	header('location:'.SITEURL.'admin/manage-admin.php');
}
else
{
	//echo "Failed to Delete Admin";
	$_SESSION['delete'] = "<div class='error'>Failed to Delete Admin. Try Again Later.</div>";
	header('location:'.SITEURL.'admin/manage-admin.php');

}

//3.Redirect to manage admin page with message

?>